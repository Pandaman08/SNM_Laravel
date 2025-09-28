<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Matricula;
use App\Models\Asistencia;
use App\Models\Periodo;
use App\Enums\AsistenciaEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class AsistenciaQRController extends Controller
{
    // Mostrar formulario para generar/regenerar QR
    public function showQRForm($id)
    {
      
        $estudiante = Estudiante::with('persona')->findOrFail($id);
        return view('pages.admin.asistencia.qr-form', compact('estudiante'));
    }

    // Generar o regenerar código QR - VERSIÓN CORREGIDA
    public function generateQR(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        // Generar código único para el QR
        $uniqueCode =$estudiante->persona->dni; // 11 caracteres
        $estudiante->qr_code = $uniqueCode;
        $estudiante->qr_generated_at = now();
        $estudiante->save();
        
        // URL corta
        $qrUrl = url('/qr-scan/' . $uniqueCode);
        
        // Configuración para QR
        $options = new QROptions([
            'version' => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_M,
            'scale' => 8,
            'imageBase64' => false,
            'imageTransparent' => false,
        ]);
        
        // Generar imagen QR
        $qrCode = new QRCode($options);
        $qrImage = $qrCode->render($qrUrl);
        
        // Guardar QR en storage - NOMBRE CON CÓDIGO QR
        $filename = 'qrcodes/' . $uniqueCode . '.png';
        
        // Asegurar que el directorio existe
        Storage::disk('public')->makeDirectory('qrcodes');
        
        // Guardar la imagen (el render() ya devuelve el contenido binario)
        Storage::disk('public')->put($filename, $qrImage);
        
        return redirect()->route('asistencia.show-qr', $estudiante->codigo_estudiante)
            ->with('success', 'Código QR generado exitosamente');
    }

    // Mostrar página para escanear QR con cámara
    public function showScanner()
    {
        $periodos = Periodo::get();
        return view('pages.admin.asistencia.scanner', compact('periodos'));
    }

    // Ruta corta para escanear QR
    public function processShortScan($code)
    {
        $estudiante = Estudiante::with('persona')->where('qr_code', $code)->first();
        
        if (!$estudiante) {
            return response()->json(['error' => 'Código QR inválido'], 404);
        }
        
        return response()->json([
            'success' => true,
            'estudiante' => [
                'codigo' => $estudiante->codigo_estudiante,
                'nombres' => $estudiante->persona->name,
                'apellidos' => $estudiante->persona->lastname
            ]
        ]);
    }

    // Procesar escaneo de QR desde formulario/cámara
    public function processScan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'id_periodo' => 'required|exists:periodos,id_periodo',
        ]);
        
        $estudiante = Estudiante::where('qr_code', $request->qr_code)->first();
        
        if (!$estudiante) {
            return back()->with('error', 'Código QR inválido');
        }
        
        $hoy = Carbon::today();
        $asistenciaExistente = Asistencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
            ->where('id_periodo', $request->id_periodo)
            ->whereDate('fecha', $hoy)
            ->first();
        
        if ($asistenciaExistente) {
            return back()->with('info', 'El estudiante ya registró asistencia hoy');
        }
        
        Asistencia::create([
            'codigo_estudiante' => $estudiante->codigo_estudiante,
            'id_periodo' => $request->id_periodo,
            'fecha' => $hoy,
            'estado' => AsistenciaEstado::PRESENTE,
            'observacion' => 'Registrado mediante QR',
        ]);
        
        return back()->with('success', 'Asistencia registrada para: ' . $estudiante->persona->nombres);
    }

    // Vista para mostrar QR del estudiante
    public function showStudentQR($id)
    {
        $estudiante = Estudiante::with('persona')->findOrFail($id);
        
        if (!$estudiante->qr_code) {
            return redirect()->route('asistencia.generate-form', $estudiante->codigo_estudiante)
                ->with('info', 'Primero debe generar un código QR para este estudiante');
        }
        
        $qrPath = 'qrcodes/' . $estudiante->qr_code . '.png';
        $qrExists = Storage::disk('public')->exists($qrPath);
        
        // Si no existe la imagen, generarla on-the-fly
        if (!$qrExists) {
            return $this->generateQRImage($estudiante);
        }
        
        return view('pages.admin.asistencia.show-qr', compact('estudiante', 'qrPath', 'qrExists'));
    }

    // Generar imagen QR si no existe
    private function generateQRImage($estudiante)
    {
        $qrUrl = url('/qr-scan/' . $estudiante->qr_code);
        
        $options = new QROptions([
            'version' => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_M,
            'scale' => 8,
            'imageBase64' => false,
        ]);
        
        $qrCode = new QRCode($options);
        $qrImage = $qrCode->render($qrUrl);
        
        $filename = 'qrcodes/' . $estudiante->qr_code . '.png';
        Storage::disk('public')->put($filename, $qrImage);
        
        return view('pages.admin.asistencia.show-qr', [
            'estudiante' => $estudiante,
            'qrPath' => $filename,
            'qrExists' => true
        ]);
    }

    // Descargar QR code
    public function downloadQR($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        if (!$estudiante->qr_code) {
            return back()->with('error', 'El estudiante no tiene código QR generado');
        }
        
        $qrPath = 'qrcodes/' . $estudiante->qr_code . '.png';
        
        if (!Storage::disk('public')->exists($qrPath)) {
            // Si no existe, generarla primero
            $this->generateQRImage($estudiante);
        }
        
        return Storage::disk('public')->download($qrPath, 'qr_' . $estudiante->qr_code . '.png');
    }

    // Mostrar imagen QR directamente
    public function showQRImage($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        if (!$estudiante->qr_code) {
            abort(404, 'Código QR no generado');
        }
        
        $qrUrl = url('/qr-scan/' . $estudiante->qr_code);
        
        $options = new QROptions([
            'version' => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_M,
            'scale' => 8,
            'imageBase64' => false,
        ]);
        
        $qrCode = new QRCode($options);
        $qrImage = $qrCode->render($qrUrl);
        
        return response($qrImage)->header('Content-Type', 'image/png');
    }

    // Función para verificar y reparar QR
    public function repairQR($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        if (!$estudiante->qr_code) {
            return back()->with('error', 'No hay código QR para reparar');
        }
        
        // Regenerar la imagen
        $qrUrl = url('/qr-scan/' . $estudiante->qr_code);
        
        $options = new QROptions([
            'version' => 10,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_M,
            'scale' => 8,
            'imageBase64' => false,
        ]);
        
        $qrCode = new QRCode($options);
        $qrImage = $qrCode->render($qrUrl);
        
        $filename = 'qrcodes/' . $estudiante->qr_code . '.png';
        Storage::disk('public')->put($filename, $qrImage);
        
        return back()->with('success', 'Imagen QR reparada exitosamente');
    }
}