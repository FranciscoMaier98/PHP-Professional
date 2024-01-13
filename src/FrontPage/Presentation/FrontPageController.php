<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Presentation;
use SocialNews\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SocialNews\FrontPage\Application\SubmissionsQuery;

final class FrontPageController
{
    private $templateRenderer;
    private $submissionsQuery;

    public function __construct(TemplateRenderer $templateRenderer, SubmissionsQuery $submissionsQuery)
    {
        $this->templateRenderer = $templateRenderer;
        $this->submissionsQuery = $submissionsQuery;
    }

    public function show(): Response
    {
        //
        //$content = 'Hello ' . $request->get('name', 'visitor');
        //return new Response($content);

        //$submissions = [
            //['url' => 'http://google.com', 'title' => 'Google'],
            //['url' => 'http://bing.com', 'title' => 'Bing'],
            //['url' => 'http://google.com', 'title' => 'Google<script>alert(1)</script>'],
        //];

        //$content = $this->templateRenderer->render('FrontPage.html.twig', ['submissions' => $submissions]);

        $content = $this->templateRenderer->render('FrontPage.html.twig', ['submissions' => $this->submissionsQuery->execute()]);
        return new Response($content);
    }
}

