<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\News;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    static $user_default = 'user.jpg';
    static $news_default = 'news.webp';
    static $diskName = 'images';

    static $systemTypes = [
        'users' => ['png', 'jpg', 'jpeg', 'gif'],
        'news' => ['gif', 'png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }

    private static function defaultAsset(String $type) {
        switch ($type) {
            case 'users':
                return asset('images/' . $type . '/' . self::$user_default);
            case 'news':
                return asset('images/' . $type . '/' . self::$news_default);
        }
    }

    private static function getFileName (String $type, int $id) {
            
        $fileName = null;
        switch($type) {
            case 'users':
                $user = User::find($id);
                $fileName = $user ? $user->profile_image : null;
                break;
            case 'news':
                $news = News::find($id);
                $fileName = $news ? $news->image : null;
                break;
            }

        return $fileName;
    }

    private static function isValidExtension ($type,  $extension) {
        return in_array($extension, self::$systemTypes[$type]);
    }

    static function upload(Request $request, int $id) {

        $validator = Validator::make($request->all(), [
            'file' => [
                function ($attribute, $value, $fail) use ($request) {

                    // 1. File must exist
                    if (!$request->hasFile('file')) {
                        return $fail('File not found');
                    }

                    // 2. Upload type must be valid
                    if (!self::isValidType($request->type)) {
                        return $fail('Unsupported upload type');
                    }

                    // 3. Extension must be valid
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();

                    if (!self::isValidExtension($request->type, $extension)) {
                        return $fail('Unsupported upload extension');
                    }
                }
            ],
        ]);

        $file = $request->file('file');

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // Hashing
        $fileName = $file->hashName(); // generate a random unique id

        switch($request->type) {
            case 'users':
                $user = User::findOrFail($id);
                $user->profile_image = $fileName;
                $user->save();
                break;
            case 'news':
                $news = News::find($id);
                $news->image = $fileName;
                $news->save();
                break;
        }

        $request->file->storeAs($request->type, $fileName, self::$diskName);
        return $fileName;
    }

    static function get(String $type, int $id) {

        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }

        // Validation: file exists in database
        $fileName = self::getFileName($type, $id);
        if ($fileName) {
            // Check if file physically exists
            $filePath = public_path('images/' . $type . '/' . $fileName);
            if (file_exists($filePath)) {
                return asset('images/' . $type . '/' . $fileName);
            }
        }

        // Not found: returns default asset
        return self::defaultAsset($type);
    }

}
