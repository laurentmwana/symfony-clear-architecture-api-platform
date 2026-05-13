<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\Resources;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\MediaType;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\RequestBody;
use ApiPlatform\OpenApi\Model\Response;
use App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor\SendVerificationPhoneProcessor;
use App\IdentityAndAccess\Presentation\Output\OtpCodeOutput;
use ArrayObject;

#[ApiResource(
   shortName: 'VerificationPhone',
   description: 'Phone verification management',
   operations: [
      new Post(
         uriTemplate: '/auth/phone/send-verification',
         name: 'auth_phone_send_verification',
         output: OtpCodeOutput::class,
         processor: SendVerificationPhoneProcessor::class,
         read: false,
         security: "is_granted('ROLE_USER')",
         status: 200,
         openapi: new OpenApiOperation(
            summary: 'Send phone verification code',
            description: 'Send a verification OTP code to the authenticated user phone number',
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
                  description: 'Phone already verified'
               ),
            ]
         ),
      ),
   ]
)]
final class VerificationPhoneResource {}
