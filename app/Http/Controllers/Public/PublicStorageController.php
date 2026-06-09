<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class PublicStorageController extends Controller
{
    public function brandLogo(string $filename): Response
    {
        return $this->serveBrandLogo('brand/logos', $filename);
    }

    public function brandLogoDark(string $filename): Response
    {
        return $this->serveBrandLogo('brand/logos-dark', $filename);
    }

    public function legacyBrandLogo(string $filename): Response
    {
        if (! preg_match('/^logo-[a-z0-9\-]+\.(svg|png|jpe?g|webp)$/i', $filename)) {
            abort(404);
        }

        $candidates = [
            storage_path('app/public/brand/logos/'.$filename),
            public_path('assets/brand/uploads/'.$filename),
        ];

        foreach ($candidates as $file) {
            if (is_file($file)) {
                return response()->file($file, [
                    'Cache-Control' => 'public, max-age=604800',
                ]);
            }
        }

        abort(404);
    }

    public function galleryImage(string $filename): Response
    {
        return $this->serveGalleryImage($filename);
    }

    public function legacyGalleryAsset(string $filename): Response
    {
        return $this->serveGalleryImage($filename);
    }

    private function serveGalleryImage(string $filename): Response
    {
        if (! preg_match('/^gallery-[a-z0-9\-]+\.(jpg|jpeg|png|webp|avif)$/i', $filename)) {
            abort(404);
        }

        $candidates = [
            storage_path('app/public/careers/gallery/'.$filename),
            public_path('assets/careers/gallery/'.$filename),
        ];

        foreach ($candidates as $file) {
            if (is_file($file)) {
                return response()->file($file, [
                    'Cache-Control' => 'public, max-age=604800',
                ]);
            }
        }

        abort(404);
    }

    private function serveBrandLogo(string $directory, string $filename): Response
    {
        if (! preg_match('/^logo(?:-dark)?-[a-z0-9\-]+\.(svg|png|jpe?g|webp)$/i', $filename)) {
            abort(404);
        }

        $file = storage_path('app/public/'.$directory.'/'.$filename);
        if (! is_file($file)) {
            abort(404);
        }

        return response()->file($file, [
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
