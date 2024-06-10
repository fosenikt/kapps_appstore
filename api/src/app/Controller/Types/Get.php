<?php
namespace Kapps\Controller\Types;

/**
 * @OA\Schema(
 *   schema="Type",
 *   type="object",
 *   @OA\Property(property="id", type="string", example="1"),
 *   @OA\Property(property="title", type="string", example="Applikasjon"),
 *   @OA\Property(property="fa_icon", type="string", example="fal fa-box-full")
 * )
 */

class Get {

	/**
     * @OA\Get(
     *     path="/types/get",
     *     tags={"Types"},
     *     summary="Get application types",
     *     description="Get all application types.",
     *     @OA\Response(
	 *         response=200,
	 *         description="Category retrieved successfully",
	 *         @OA\JsonContent(type="object", ref="#/components/schemas/Type")
	 *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Apps not found"
     *     )
     * )
	 */
	public function get_types()
	{
		$obj = new \Kapps\Model\Types\Get();
		return $obj->get_types();
	}

}