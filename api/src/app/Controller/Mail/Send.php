<?php
namespace Kapps\Controller\Mail;

use \Kapps\Model\Auth\User as AuthUser;

class Send {

    private $AuthUser;

    public function __construct()
    {
        $this->AuthUser = new AuthUser;
        if (!$this->AuthUser->isAuthenticated()) {
            header('HTTP/1.0 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Access denied']);
            exit;
        }
    }

   /**
     * @OA\Get(
     *     path="/mail/send",
     *     tags={"Mail"},
     *     summary="Send emails (GET)",
     *     description="Triggers sending of emails stored in the database. This can be a login code or similar. This endpoint is typically used internally by a scheduler.",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Emails processed"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access denied"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function send()
    {
        $obj = new \Kapps\Model\Mail\Send();
        return $obj->send();
    }
}
?>
