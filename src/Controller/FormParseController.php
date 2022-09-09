<?php

namespace App\Controller;

use App\Entity\FormParse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormParseController extends AbstractController
{
    #[Route('/form/parse', name: 'app_form_parse')]
    public function new(Request $request, ManagerRegistry $doctrine,): Response
    {

        $parse = new FormParse();
        $parse->setInputText('');
        $parse->setOutputText('');

        $form = $this->createFormBuilder($parse)
            ->add('inputText', TextareaType::class, [
                'label' => 'incoming text',
                'required' => false,
            ])
            ->add('outputText', TextareaType::class, [
                'label' => 'Exception word',
                'required' => false,


            ])
            ->add('send', SubmitType::class, [
                'label' => 'Parse',

            ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $jsonFile = $form->get('inputText')->getData();
            $wordException = $form->get('outputText')->getData();
//              $jsonFile = $request->request->get('inputText');

            if (!empty($jsonFile & $wordException)) {
                $textF = preg_replace('#\b[A-Za-zЁёА-я]{1,2}\b\s*#', '', $jsonFile);// убирает элементы меньше 3 символов
                $textException = str_word_count($wordException, 1, '0...3');
                $exception = str_replace($textException, '', $textF);
                $jsonParse = str_word_count($exception, 1, '0...3');
                $jsonFile = array_count_values($jsonParse);
                $repeat = array_diff($jsonFile, [1]);
                $jsonFile = $repeat;
                $jsonFile = '0';
                return $this->render( 'form_parse/index.html.twig', [

                    'json' => $jsonFile,
                    'form' => $form->createView(),
                ]);
            } else {
                $this->redirectToRoute('form_parse/index.html.twig');
            }


        }

        return $this->render('form_parse/index.html.twig', array('form' => $form->createView()));
    }
}
