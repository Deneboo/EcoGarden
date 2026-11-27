<?php

namespace App\Validator\PostCode;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class PostCodeValidator extends ConstraintValidator
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%geo_api_gouv_url%')]
        private string $apiUrl
    ) {}
    
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        try {
            $response = $this->client->request('GET', $this->apiUrl, [
                'query' => [
                    'codePostal' => $value,
                ],
            ]);

            $data = $response->toArray();

            if (empty($data)) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }

        } catch (\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface $e) {
            throw new \RuntimeException(
                'Impossible de contacter le service de validation des codes postaux.',
                previous: $e
            );
        }
    }
}
