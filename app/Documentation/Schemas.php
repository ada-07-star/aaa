<?php

/**
 * @OA\Schema(
 *     schema="IdeaDetail",
 *     title="Idea Detail",
 *     description="جزئیات کامل یک ایده",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(
 *         property="topic_id",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="title", type="string", example="موضوع نمونه"),
 *         @OA\Property(property="id", type="integer", example=1)
 *     ),
 *     @OA\Property(property="title", type="string", example="عنوان ایده نمونه"),
 *     @OA\Property(property="description", type="string", example="توضیحات کامل درباره ایده"),
 *     @OA\Property(property="is_published", type="boolean", example=true),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2023-05-01T12:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="current_state",
 *         type="array",
 *         @OA\Items(type="string", enum={"در حال بررسی", "تایید شده", "رد شده", "در حال اجرا", "تکمیل شده"}, example="در حال بررسی")
 *     ),
 *     @OA\Property(
 *         property="participation_type",
 *         type="array",
 *         @OA\Items(type="string", enum={"عمومی", "خصوصی", "تیمی"}, example="عمومی")
 *     ),
 *     @OA\Property(
 *         property="users",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *         @OA\Property(property="name", type="string", example="نام کاربر"),
 *         @OA\Property(property="email", type="string", example="user@example.com")
 *     )
 * )
 */

/**
 * @OA\Schema(
 *     schema="IdeaList",
 *     title="Idea List",
 *     description="لیست ایده‌ها",
 *     type="array",
 *     @OA\Items(ref="#/components/schemas/IdeaDetail")
 * )
 */