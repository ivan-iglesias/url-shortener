<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class AuthController
 *
 * @Route("/api")
 */
class AuthController extends AbstractController
{
    /**
     * Autenticación del usuario para obtener el token.
     *
     * @Route("/login_check", name="user_login_check", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve el token."
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Credenciales incorrectas."
     * )
     * @SWG\Response(
     *     response=500,
     *     description="Error interno."
     * )
     * @SWG\Parameter(
     *     name="username",
     *     in="body",
     *     description="Nombre del usuario",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="username", type="string"),
     *          @SWG\Property(property="password", type="string")
     *      )
     * )
     * @SWG\Tag(name="user")
     */
    public function index() { }
}
