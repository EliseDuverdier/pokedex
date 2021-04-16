<?php

namespace App\Infra\Validator\Constraints;

use App\Infra\Repository\PokemonRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TypeValidator extends ConstraintValidator
{
    public function __construct(
        protected PokemonRepository $repository
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Type) {
            throw new UnexpectedTypeException($constraint, Type::class);
        }

        if (empty($value)) {
            return;
        }

        $acceptedTypes = $this->repository->getTypesName();
        if (!in_array($value, $acceptedTypes)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ type }}', $value)
                ->setParameter('{{ available_types }}', implode(', ', $acceptedTypes))
                ->addViolation();
        }
    }
}
