<?php declare(strict_types=1);

namespace SocialNews\FrontPage\Presentation;
use SocialNews\Framework\Rendering\TemplateRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class FrontPageController
{
    private $templateRenderer;
    private $submissionQuery;

    public function __construct(TemplateRenderer $templateRenderer, Submission $submissionQuery)
    {
        $this->templateRenderer = $templateRenderer;
        $this->submissionQuery = $submissionQuery;
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

        $content = $this->templateRenderer->render('FrontPage.html.twig', ['submissions' => $this->submissionQuery->execute()]);
        return new Response($content);
    }
}

