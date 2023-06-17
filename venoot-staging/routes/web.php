<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

# Custom Woocommerce Password Reset

use Illuminate\Support\Facades\Route;

Route::get('/password-reset/{order_token}', 'UserController@resetPassword');

## Webinar password change
Route::get('/password-change/{order_id}', 'UserController@webinarPasswordChange');
Route::get('/password-changed/{order_id}', 'UserController@webinarPasswordChanged');

# PAYMENT TESTING
Route::get('/paypal/status', ['as' => 'status', 'uses' => 'PaypalController@getPaymentStatus']);

## Pretty URL
Route::get('landings/{pretty_url}', 'EventController@prettyUrl');

## Supervisor mails
Route::get('participations/{participation}/accept', 'EventController@supervisorAccept');
Route::get('participations/{participation}/reject', 'EventController@supervisorreject');
Route::get('participations/{participation}/choices/{choice_index}', 'EventController@supervisorChange');
Route::get('testSuperviseMail', 'EventController@testSuperviseMail');

## Supervisor Tickets
Route::get('databases/{database}/events/{event}/{uuid}/accept', 'TicketController@acceptParticipant');
Route::get('databases/{database}/events/{event}/{uuid}/reject', 'TicketController@rejectParticipant');

Route::get('events/{event}/landing', 'EventController@landing');
Route::get('events/{event}/landing/edit', 'EventController@landingEdit');
Route::get('events/{event}/share/{service}', 'EventController@share');

## Zoho Routes
Route::get('events/{event}/confirms/{zoho_id}', 'WebinarController@confirmPositive');
Route::get('events/{event}/unconfirms/{zoho_id}', 'WebinarController@confirmNegative');

Route::get('confirms/{uuid}', 'EventController@confirms');
Route::get('invitees/{uuid}', 'EventController@invitees');
Route::get('unconfirms-brazil/{uuid}', 'EventController@unconfirmsBrazil');
Route::get('unconfirms/{uuid}', 'EventController@unconfirms');
Route::get('qr/{uuid}', 'EventController@qr');
Route::get('qr-brazil/{uuid}', 'EventController@qrBrazil');
Route::get('qr/bought-ticket/{uuid}', 'EventController@qrBought');
Route::get('events/{event}/polls/{poll}/show', 'EventController@showPoll');

# Backup Mails Brazil
Route::get('invitation-brazil/{uuid}', 'EventController@invitationBrazil');
Route::get('confirmation-brazil/{uuid}', 'EventController@confirmationBrazil');

# Test Route for QR
Route::get('qr-test/{uuid}', 'EventController@qrTest');

Route::get('tracker', 'EventController@trackerInfo');

Route::get('events/{event}/confirmation/preview', 'EventController@confirmationPreview');
Route::get('events/{event}/qr/preview', 'EventController@qrPreview');
Route::get('events/{event}/reconfirmation/preview', 'EventController@reconfirmationPreview');

Route::group(['middleware' => 'auth:api'], function () {
    # Landing Preview
    Route::get('events/{event}/landing/preview/{which}', 'EventController@landingPreview');

    # Builder
    Route::get('templates/{template}/builderjs', 'TemplateController@builder');
    Route::get('templates/{template}', 'TemplateController@builderTemplate');
    Route::get('webinars/{webinar}', 'WebinarController@landing');
});

Route::get('templates/{template}/preview', 'TemplateController@mailPreview');

## EXTERNAL DATABASE ACCESS
Route::get('databases/{database}/external', 'DatabaseController@externalAccessView');

## AUTO PRINT
Route::get('participations/autoprint/{uuid}', 'ParticipantController@printQrWithTemplate');
Route::get('participations/testprint/{uuid}', 'ParticipantController@testPrintQrWithTemplate');

## IMAGE
Route::get('public/images/{image}', 'UploadController@getPublicImage');

Route::get('databases/{database}/exportView', 'DatabaseController@exportView');

# Unsubscribe
Route::get('unsubscribe/{uuid}', 'EventController@voluntarySupression');

if (getenv('APP_ENV') == 'local') {
    Route::get('testCorreo', 'EventController@testCorreoAsView');
}

## Chat
Route::get('venoot-chat', 'StreamingController@venootChat');

## Webinar
// Route::get('venoot-webinar', 'WebinarController@tempVenootWebinarLanding');
Route::get('venoot-webinar', 'WebinarController@venootWebinar');

## Participant Login
Route::get('participant-user', 'ParticipantUserController@participantUser');

## Template to PDF
Route::get('templates/{template}/{uuid}', 'EventController@templateToPDF');
Route::get('templates/{template}/{uuid}/html', 'EventController@templateToHTML');
Route::get('templates/{template}/html', 'EventController@rawTemplateToHTML');

/**
 *  Venoot Static Page
 */
Route::get('/', 'StaticController@index');
Route::get('static/{lang}', 'StaticController@index');

Route::fallback('StaticController@fallback');
