<?php

namespace App\Controller\api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use App\Entity\Url;
use App\Entity\Activity;
use App\Services\ShortenerService;
use App\Services\Helpers;

/**
 * Class UrlController
 *
 * @Route("/api/url")
 */
class UrlController extends AbstractController
{
    /**
     * Devuelve un array de URLs con sus estadisticas asociadas.
     *
     * @Route("", name="url", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Devuelve un array de URLs."
     * )
     * @SWG\Parameter(
     *     name="orderby",
     *     in="query",
     *     type="string",
     *     description="[OPCIONAL] Campo por el que ordenar",
     * )
     * @SWG\Tag(name="url")
     */
    public function index(Request $request)
    {
        $orderBy = $request->query->get('orderby');

        $urls = $this->getDoctrine()
            ->getRepository(Url::class)
            ->findAllWithStatistics($orderBy);

        return $this->json($urls, 200, []);
    }

    /**
     * Redirige una la URL acortada.
     *
     * @Route("/{urlshort}", name="url_show", methods={"GET"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="Se redirige correctamente."
     * )
     * @SWG\Response(
     *     response=404,
     *     description="La URL corta no existe en la base de datos."
     * )
     * @SWG\Parameter(
     *     name="urlshort",
     *     in="path",
     *     type="string",
     *     description="URL corta"
     * )
     * @SWG\Tag(name="url")
     */
    public function show(string $urlshort)
    {
        $url = $this->getDoctrine()
            ->getRepository(Url::class)
            ->findOneBy(['nameshort' => $urlshort]);

        if (is_null($url)) {
            return $this->json("¡URL no encontrada!", 404, []);
        }

        $device = Helpers::isMobile($_SERVER['HTTP_USER_AGENT'])
            ? 'smartphone'
            : 'computer';

        $this->getDoctrine()->getRepository(Activity::class)->create($url, $device);

        return $this->redirect('http://' . $url->getNamelong());
    }

    /**
     * Añade una URL a la base de datos acortada por la estrategia indicada.
     *
     * @Route("", name="url_store", methods={"POST"})
     *
     * @SWG\Response(
     *     response=200,
     *     description="URL añadida correctamente."
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Falta el campo url de entrada."
     * )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     type="string",
     *     description="Bearer {jwt}",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="url",
     *     in="formData",
     *     type="string",
     *     description="URL a acortada",
     *     required=true
     * )
     * @SWG\Parameter(
     *     name="strategy",
     *     in="formData",
     *     type="string",
     *     description="[OPCIONAL] Estrategia a usar (UrlToNumber, UrlToAlphanumeric)"
     * )
     * @SWG\Tag(name="url")
     */
    public function store(Request $request)
    {
        $urllong = $request->request->get('url');

        if (is_null($urllong)) {
            return $this->json('¡Falta el campo url!', 400);
        }

        $service = new ShortenerService(
            $request->request->get('strategy', 'default')
        );

        $urllong = Helpers::removeHttpText($urllong);

        $url = new Url();
        $url->setNamelong($urllong);
        $url->setNameshort($service->GetShortUrl($urllong));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($url);
        $manager->flush();

        return $this->json('URL guardada: ' . $url->getNameshort(), 200);
    }
}
