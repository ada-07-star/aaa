<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

  public function show(User $user, $id)
  {
    //
  }
  /**
   * @OA\Put(
   *     path="/api/v1/app/users/{id}",
   *     tags={"Users"},
   *     summary="به‌روزرسانی نام کاربر",
   *     description="این endpoint برای به‌روزرسانی نام کاربر با شناسه مشخص استفاده می‌شود.",
   *     @OA\Parameter(
   *         name="id",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="integer")
   *     ),
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             @OA\Property(property="name", type="string", example="dav")
   *         )
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="کاربر با موفقیت به‌روزرسانی شد",
   *         @OA\JsonContent(ref="#/components/schemas/User")
   *     ),
   *     @OA\Response(response=400, description="ورودی نامعتبر"),
   *     @OA\Response(response=404, description="کاربر پیدا نشد")
   * )
   */
  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);

    // اعتبارسنجی ورودی
    $request->validate([
      'name' => 'required|string|max:255',
    ]);

    // به‌روزرسانی نام کاربر
    $user->name = $request->input('name');
    $user->save();

    // بازگشت پاسخ
    return response()->json($user, 200);
  }
}
