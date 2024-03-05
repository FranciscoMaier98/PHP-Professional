<?php declare(strict_types=1);

namespace SocialNews\Submission\Presentation;

//use SocialNews\Framework\Csrf\StoredTokenValidator;
//use SocialNews\Framework\Csrf\Token;

use SocialNews\Submission\Application\SubmitLinkHandler;
//use SocialNews\Submission\Application\SubmitLink;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SocialNews\Framework\Rendering\TemplateRenderer;
use SocialNews\Framework\Rbac\User;
use SocialNews\Framework\Rbac\Permission;
use SocialNews\Framework\Rbac\AuthenticatedUser;
use Symfony\Component\HttpFoundation\Session\Session;

final class SubmissionController
{
    private $templateRenderer;
    private $submissionFormFactory;
    private $session;
    private $submitLinkHandler;
    private $user;

    public function __construct(
        TemplateRenderer $templateRenderer, 
        //StoredTokenValidator $storedTokenValidator, 
        SubmissionFormFactory $submissionFormFactory,
        Session $session,
        SubmitLinkHandler $submitLinkHandler,
        User $user
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->submissionFormFactory = $submissionFormFactory;
        $this->session = $session;
        $this->submitLinkHandler = $submitLinkHandler;
        $this->user = $user;
    }

    public function show(): Response
    {
        if(!$this->user->hasPermission(new Permission\SubmitLink())) {
            $this->session->getFlashBag()->add(
                'errors',
                'You have to log in before you can submit a link.'
            );
            return new RedirectResponse('/login');
        }

        $content = $this->templateRenderer->render('Submission.html.twig');
        return new Response($content);
    }

    public function submit(Request $request): Response
    {
        if(!$this->user->hasPermission(new Permission\SubmitLink())) {
            $this->session->getFlashBag()->add(
                'errors',
                'You have to log in before you can submit a link.'
            );
            return new RedirectResponse('/login');
        }
        
        $response = new RedirectResponse('/submit');
        
        $form = $this->submissionFormFactory->createFromRequest($request);
        
        if($form->hasValidationErrors()){
            foreach($form->getValidationErrors() as $errorMessage) {
                $this->session->getFlashBag()->add('errors', $errorMessage);
            }
            return $response;
        }

        if(!$this->user instanceof AuthenticatedUser) {
            throw new \LoginException('Only authenticated users can submit links');
        }

        $this->submitLinkHandler->handle($form->toCommand($this->user));

        $this->session->getFlashBag()->add(
            'success',
            'Your URL was submitted successfully'
        );

        return $response;
    }
/*
    public function submit(Request $request): Response
    {
        $response = new RedirectResponse('/submit');

        if(!$this->storedTokenValidator->validate(
            'submission',
            new Token((string)$request->get('token'))
        ))
        {
            $this->session->getFlash()->add('errors', 'Invalid token');
            return $response;
        }
        
        $this->submitLinkHandler->handle(new SubmitLink(
            $request->get('url'),
            $request->get('title')
        ));

        $this->session->getFlashBag()->add(
            'success',
            'Your URL was submitted successfully'
        );
        return $response;
    }
    */
}