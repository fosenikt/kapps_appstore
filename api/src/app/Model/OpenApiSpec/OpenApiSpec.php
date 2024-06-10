<?php
namespace Kapps\Model\OpenApiSpec;

/**
 * @OA\Tag(
 *     name="Apps",
 *     description="Applications"
 * )
 * @OA\Tag(
 *     name="Auth",
 *     description="User validation and authentication"
 * )
 * @OA\Tag(
 *     name="Companies",
 *     description="Companies / Municipalities"
 * )
 * @OA\Tag(
 *     name="Delivery",
 *     description="How the application can be delivered, like SaaS, on-premise, etc..."
 * )
 * @OA\Tag(
 *     name="Employees",
 *     description="Get registered employees / contact persons for application"
 * )
 * @OA\Tag(
 *     name="Licenses",
 *     description="Application licenses"
 * )
 * @OA\Tag(
 *     name="Mail",
 *     description="Handle mail"
 * )
 * @OA\Tag(
 *     name="Municipality",
 *     description="Municipalities"
 * )
 * @OA\Tag(
 *     name="Search",
 *     description="Search"
 * )
 * @OA\Tag(
 *     name="Stats",
 *     description="Statistics"
 * )
 * @OA\Tag(
 *     name="Types",
 *     description="Application types (aka. categories). E.g. Applikasjon, Integrasjon, RPA, Dokument, Skript..."
 * )
 * @OA\Tag(
 *     name="Upload",
 *     description="Upload files"
 * )
 * @OA\Tag(
 *     name="Users",
 *     description="Users"
 * )
 * @OA\Tag(
 *     name="Users Admin",
 *     description="User-administration"
 * )
 * @OA\Info(
 *     version="1.0",
 *     title="Kapps API",
 *     description="General API backend for Kapps",
 *     @OA\Contact(name="Fosen IKT")
 * )
 * @OA\Server(
 *     url="https://appsapi.kapps.no",
 *     description="API server"
 * )
 * @OA\Server(
 *     url="https://appsapi.kapps.local",
 *     description="Development DNS"
 * )
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="JWT Authorization header using the Bearer scheme."
 *     ),
 *     @OA\Schema(schema="DefaultSuccessResponse", type="object",
 *         @OA\Property(property="status", type="string", example="success"),
 *         @OA\Property(property="status_code", type="integer", example=200)
 *     ),
 *     @OA\Schema(schema="DefaultBadRequest", type="object",
 *         @OA\Property(property="status", type="string", example="Invalid request"),
 *         @OA\Property(property="status_code", type="integer", example=400)
 *     ),
 *     @OA\Schema(schema="DefaultUnauthorized", type="object",
 *         @OA\Property(property="status", type="string", example="Not logged in"),
 *         @OA\Property(property="status_code", type="integer", example=401)
 *     ),
 *     @OA\Schema(schema="DefaultForbiddenRequest", type="object",
 *         @OA\Property(property="status", type="string", example="Form input missing"),
 *         @OA\Property(property="status_code", type="integer", example=403)
 *     ),
 *     @OA\Schema(schema="DefaultMethodNotAllowed", type="object",
 *         @OA\Property(property="status", type="string", example="Method not allowed"),
 *         @OA\Property(property="status_code", type="integer", example=405)
 *     ),
 * ),
 * security={
 *     {"tenantId": {}}
 * }
 */
class OpenApiSpec
{
}