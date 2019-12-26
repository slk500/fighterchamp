<?php


namespace AppBundle\Service;

use AppBundle\ApiProblem\ApiProblem;
use AppBundle\ApiProblem\ApiProblemException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

//todo refactor
class ApiFormService
{
    public function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if (null === $data) {
            $apiProblem = new ApiProblem(
                400,
                ApiProblem::TYPE_INVALID_REQUEST_BODY_FORMAT
            );

            throw new ApiProblemException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    public function throwValidationErrorsResponse(FormInterface $form, Request $request)
    {
        $arrayData = json_decode($request->getContent(), true);

        $data = array_flip($arrayData);

        $children = $form->all();

        $arr = [];

        foreach ($children as $ch) {
            $arr []= $ch->getName();
        }

        $data = array_diff($data, $arr);

        $errors = $this->getErrorsFromForm($form);

        array_push($errors, ['extra_fields' => $data]);

        $apiProblem = new ApiProblem(
            400,
            ApiProblem::TYPE_VALIDATION_ERROR
        );
        $apiProblem->set('errors', $errors);

        throw new ApiProblemException($apiProblem);
    }

    public function getErrorsFromForm(FormInterface $form) : array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
