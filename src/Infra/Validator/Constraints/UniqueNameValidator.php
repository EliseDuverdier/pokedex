<?php

namespace App\Infra\Validator\Constraints;

use App\Infra\Repository\PokemonRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueNameValidator extends ConstraintValidator
{
    public function __construct(
        protected PokemonRepository $repository
    ) {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueName) {
            throw new UnexpectedTypeException($constraint, UniqueName::class);
        }

        if (empty($value)) {
            return;
        }

        $existingNames = $this->repository->getPokemonNames();
        if (in_array($value, $existingNames)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ name }}', $value)
                ->addViolation();
        }
    }
}
