<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($path)
    {
        // 1. Cek apakah file benar-benar ada di Filebase
        if (!Storage::disk('filebase')->exists($path)) {
            abort(404);
        }

        // 2. Buat URL sementara yang hanya berlaku selama 5 menit
        $temporaryUrl = Storage::disk('filebase')->temporaryUrl(
            $path,
            now()->addMinutes(5)
        );

        // 3. Arahkan browser pengunjung ke URL sementara tersebut
        return redirect($temporaryUrl);
    }
}