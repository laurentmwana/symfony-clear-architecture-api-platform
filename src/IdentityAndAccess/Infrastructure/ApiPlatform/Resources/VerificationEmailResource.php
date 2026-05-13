<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\SendVerificationEmailProcessor;
use App\IdentityAndAccess\Presentation\Output\OtpCodeOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'VerificationEmail',
   description: 'Email verification management',
   operations: [
      new Post(
         uriTemplate: '/auth/email/send-verification',
         name: 'auth_email_send_verification',
         output: OtpCodeOutput::class,
         processor: SendVerificationEmailProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Send email verification code',
            description: 'Send a verification OTP code to the authenticated user email',
            requestBody: new RequestBody(
               content: new ArrayObject([
                  'application/json' => new MediaType(
                     new ArrayObject([
                        'type' => 'object',
                        'properties' => [],
                     ])
                  )
               ])
            ),
            responses: [
               '200' => new Response(
                  description: 'Verification code sent successfully',
                  content: new ArrayObject([
                     'application/json' => new MediaType(
                        new ArrayObject([
                           'type' => 'object',
                           'properties' => [
                              'message' => [
                                 'type' => 'string',
                              ],
                              'expires_minutes' => [
                                 'type' => 'integer',
                              ],
                              'attempts' => [
                                 'type' => 'integer',
                              ],
                           ],
                        ])
                     ),
                  ])
               ),
               '401' => new Response(
                  description: 'Unauthorized'
               ),
               '422' => new Response(
                  description: 'Email already verified'
               ),
            ]
         ),
      ),
   ]
)]
final class VerificationEmailResource {}
