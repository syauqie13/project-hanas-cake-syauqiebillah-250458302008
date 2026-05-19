<?php

namespace App\Traits;

/**
 * Trait ApiResponseTrait
 *
 * Standarisasi format JSON response untuk seluruh API endpoint.
 * Semua controller API wajib menggunakan trait ini agar format response konsisten.
 */
trait ApiResponseTrait
{
    /**
     * Response sukses standar
     *
     * @param mixed       $data    Data yang dikembalikan
     * @param string      $message Pesan sukses
     * @param int         $code    HTTP status code (default: 200)
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Berhasil', int $code = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        // Hanya sertakan 'data' jika bukan null
        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Response error standar
     *
     * @param string      $message Pesan error
     * @param int         $code    HTTP status code (default: 400)
     * @param mixed       $errors  Detail error (opsional, misal validation errors)
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = 'Gagal', int $code = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Response untuk data yang tidak ditemukan (404)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse(string $message = 'Data tidak ditemukan')
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Response untuk akses ditolak (403)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbiddenResponse(string $message = 'Akses ditolak')
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * Response untuk unauthorized (401)
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorizedResponse(string $message = 'Tidak terautentikasi')
    {
        return $this->errorResponse($message, 401);
    }
}
