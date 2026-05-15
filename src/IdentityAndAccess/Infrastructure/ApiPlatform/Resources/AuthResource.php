<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\{State\Processor\LoginProcessor};
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'IdentityAndAccess',
   description: 'Auth User',
   operations: [
      new Post(
         uriTemplate: '/auth/login',
         name: 'auth_login',
         input: LoginInput::class,
         output: JwtTokenOutput::class,
         processor: LoginProcessor::class,
         read: false,
         security: "is_granted('PUBLIC_ACCESS')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'User authentication',
            description: 'Authenticate user with email or phone number and password',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [
                           'identifier' => ['type' => 'string'],
                           'password' => ['type' => 'string'],
                        ],
                        'required' => ['identifier', 'password']
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Authentication successful',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'token' => ['type' => 'string'],
                              'refresh_token' => ['type' => 'string']
                           ]
                        ])
                     )
                  ])
               ),
               '400' => new Response(description: 'Bad request - Validation error'),
               '401' => new Response(description: 'Invalid credentials'),
            ]
         ),
      )
   ]
)]
final class AuthResource {}
