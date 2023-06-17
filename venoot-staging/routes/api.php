<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Route;

# FAKE MAIL TRIGGER ROUTER FOR NEW WEBINAR
Route::post('webinars/{event}/request-email', 'WebinarController@emailRequest');

## NDT ACCESS
Route::get('database/{database}/external', 'DatabaseController@externalAccess');
Route::post('webinars/{event}/movement-data', 'WebinarController@compile_data');
Route::post('participants/add-zoho-id', 'ParticipantController@addZohoId');
Route::get('events/{event}/qr-image/{zoho_id}', 'WebinarController@qrImage');
Route::post('venoot-woocommerce', 'UserController@storeFromApi');
Route::post('webinars/password-change', 'UserController@webinarPasswordChangeRequest');

## Landing
Route::get('events/{event}/landing', 'EventController@apiLanding');

if (config('app.env') !== 'staging') {
    Route::post('register', 'Auth\RegisterController@register');
}
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('login', 'AuthController@login');
Route::get('refresh', 'AuthController@refresh');
Route::post('logout', 'AuthController@logout');
Route::group([
    'namespace' => 'Auth',
    'prefix' => 'password'
], function () {
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});


Route::get('companieslimited', 'CompanyController@indexLimited');

Route::post('participants/confirmFromForm', 'EventController@confirmFromForm');
Route::post('participants/invitees', 'EventController@sendInvitees');
Route::get('events/{event}/participants', 'EventController@participants');

## Ticket Sales
Route::get('events/{event}/database', 'EventController@database');
Route::get('events/{event}/tickets-cart', 'TicketController@eventIndex');
Route::post('events/{event}/webpay/buy', 'WebpayController@buyTickets');
Route::get('events/{event}/webpay/response', 'WebpayController@responseTickets');
//Route::post('events/{event}/webpay/finish', 'WebpayController@finishTickets');
Route::post('events/{event}/paypal/buy', ['as' => 'payment', 'uses' => 'PaypalController@payWithpaypal']);
Route::post('events/{event}/paypal/response', 'PaypalController@getExpressCheckoutSuccess');
Route::post('events/{event}/paypal/finish', 'PaypalController@finishTickets');
Route::post('discounts/{discount}/check-cupon', 'DiscountController@checkCupon');
Route::post('discounts/{discount}/check-key-value', 'DiscountController@checkKeyValue');
Route::post('tickets/{ticket}/check-key', 'TicketController@checkKey');
Route::post('tickets/{ticket}/check-email', 'TicketController@checkEmail');
Route::get('check-access/{uuid}', 'TicketController@checkAccess');


## Encuestas
Route::post('events/{event}/polls/checkUUID', 'PollController@checkUUID');
Route::get('events/{event}/polls/{poll}', 'PollController@externalGet');
Route::post('events/{event}/polls/{poll}', 'PollController@answer');
Route::post('events/{event}/participantFromPoll', 'PollController@participantFromPoll');

// WEBPAY
Route::get('webpay/response', 'WebpayController@response');
// Route::post('webpay/finish', 'WebpayController@finish');

## MAilgun
Route::post('mailgun/webhook', 'SentEmailController@handleWebhook');

## Imagenes Claudio
Route::get('events/{event}/images', 'EventController@indexImages');
Route::post('uploadExtraImages', 'UploadController@uploadExtraImages');
Route::post('events/{event}/updateExtraImages', 'EventController@updateExtraImages');

## APP
Route::get('events/{event}/app/{code}', 'EventController@app');
Route::post('events/{event}/signIn', 'EventController@signIn');
Route::post('producer/login', 'EventController@producerLogin');
Route::post('participants/{participant}/subscribe', 'ParticipantController@subscribe');
Route::post('participants/{participant}/unsubscribe', 'ParticipantController@unsubscribe');
Route::post('events/{event}/push', 'EventController@push');
Route::get('events/{event}/notifications', 'EventController@push_notifications');

## APP Reuniones
Route::get('events/{event}/meetings', 'EventController@indexMeetings');
Route::post('events/{event}/meetings/{meeting}', 'EventController@takeMeeting');

## APP QUESTIONS
Route::get('events/{event}/appQuestions', 'EventController@getAppQuestions');
Route::get('activity/{activity}/appQuestions', 'ActivityController@getAppQuestions');
Route::post('activity/{activity}/appQuestions', 'ActivityController@addAppQuestion');
Route::post('appQuestion/{appQuestion}/toggle', 'ActivityController@toggleAppQuestion');
Route::delete('appQuestion/{appQuestion}', 'ActivityController@deleteAppQuestion');

## APP CHAT
Route::get('people/{event}', 'EventController@people');
Route::post('events/{event}/messages/unread', 'EventController@unreadMessages');
Route::post('events/{event}/messages', 'EventController@chatMessages');

## Locale
Route::post('locale', 'UserController@changeLocale');

## Contact Mail
Route::post('events/{event}/contact', 'EventController@contactMail');

## Video
Route::post('events/{event}/participants/secureVideo', 'EventController@participantsSecureVideo');
Route::post('events/{event}/participants/doneVideo', 'EventController@participantsDoneVideo');

## Landing Participant Access
Route::get('events/{event}/landing/qr', 'EventController@participantByQr');

## SSO LOGIN
Route::post('sso_token', 'AuthController@ssoTokenLogin');

## Guest Join Request Webinar
Route::post('meetings/guest-link-join', 'WebinarController@joinWithGuestLink');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('meetings', 'WebinarController@getMeetings');
    Route::post('meetings', 'WebinarController@createMeeting');
    Route::post('meetings/join', 'WebinarController@join');
    Route::post('meetings/close', 'WebinarController@close');

    Route::get('sso_token', 'AuthController@ssoTokenRequest');

    Route::get('user', 'AuthController@user');
    Route::get('users/requested', 'UserController@requested');
    Route::get('users/{user}/accept', 'UserController@accept');
    Route::get('users/{user}/reject', 'UserController@reject');
    Route::get('users/roles', 'UserController@indexWithRoles');
    Route::post('users/{user}/addRole', 'UserController@addRole');
    Route::post('users/{user}/removeRole', 'UserController@addRole');
    Route::apiResource('users', 'UserController')->except(['store']);

    Route::apiResource('companies', 'CompanyController');

    Route::apiResource('actors', 'ActorController');
    Route::get('actorsByEvent/{id}', 'ActorController@actorsByEvent');


    Route::apiResource('sponsors', 'SponsorController');

    Route::get('databases/keys', 'DatabaseController@indexWithAccessKeys');
    Route::post('databases/advanced', 'DatabaseController@advanced');
    Route::apiResource('databases', 'DatabaseController');
    Route::post('databases/{database}/externalAccess', 'DatabaseController@updateExternalAccess');
    Route::patch('databases/{database}/externalAccess', 'DatabaseController@updateAccessKey');
    Route::apiResource('databases.participants', 'ParticipantController');
    Route::post('databases/{database}/participants/massStore', 'ParticipantController@massStore');
    Route::resource('databases.profiles', 'ProfileController')->only(['store', 'update']);
    Route::get('databases/{database}/export', 'DatabaseController@export');
    Route::get('profiles/{profile}/participants', 'ProfileController@participants');
    Route::get('profiles/{profile}/database', 'ProfileController@database');

    Route::get('events/only', 'EventController@eventsOnlyIndex');
    Route::apiResource('events', 'EventController');
    Route::get('activitiesByEvent/{id}', 'ActivityController@activitiesByEvent');

    Route::post('events/paginated', 'EventController@paginatedIndex');
    Route::post('events/{event}/publish', 'EventController@publish');
    Route::apiResource('events.activities', 'ActivityController');
    Route::match(['put', 'patch'], 'events/{event}/sponsors', 'EventController@addSponsors');
    Route::post('events/{event}/poll/{poll}/participants', 'EventController@getPollParticipants');
    Route::post('events/{event}/poll/{poll}', 'EventController@sendPoll');
    Route::get('events/{event}/polls', 'PollController@eventIndex');
    Route::get('events/{event}/invite', 'EventController@invite');
    Route::post('events/{event}/confirmation', 'EventController@confirmation');
    Route::post('events/{event}/qr', 'EventController@resendQr');
    Route::post('events/{event}/reconfirmation', 'EventController@reconfirmation');
    Route::post('events/{event}/free', 'EventController@free');
    Route::post('events/{event}/scheduled', 'EventController@scheduled');
    Route::get('events/{event}/participants/series', 'EventController@participantsSeries');
    Route::get('events/{event}/participants/confirmation', 'EventController@participantsConfirmation');
    Route::get('events/{event}/participants/reconfirmation', 'EventController@participantsReconfirmation');
    Route::get('events/{event}/participants/qr', 'EventController@participantsQr');
    Route::get('events/{event}/secureVideoViews', 'EventController@getSecureVideoViews');
    Route::get('events/{event}/tickets', 'EventController@tickets');
    Route::post('events/{event}/landing', 'EventController@updateLanding');
    Route::get('events/{event}/devices', 'DeviceController@index');
    Route::post('events/{event}/devices', 'DeviceController@store');
    Route::post('events/{event}/export', 'EventController@export');
    Route::get('events/{event}/exportVideoParticipants', 'EventController@exportVideoParticipants');
    Route::post('events/{event}/exportConfirmation', 'EventController@exportConfirmation');
    Route::post('events/{event}/exportTicketSale', 'EventController@exportTicketSale');
    Route::post('events/{event}/exportApp', 'EventController@exportApp');
    Route::get('events/{event}/exportBounced', 'EventController@exportBounced');
    Route::get('events/{event}/polls/{poll}/export', 'EventController@exportPoll');
    Route::post('events/{event}/suppressEmails', 'EventController@suppressEmails');
    Route::post('events/{event}/meetingWindow', 'EventController@createMeetingWindow');
    Route::post('events/{event}/meetings/{meeting}/attendant', 'EventController@assignAttendant');
    Route::delete('events/{event}/meetings/{meeting}', 'EventController@deleteMeeting');
    Route::post('events/advanced', 'EventController@advanced');

    Route::delete('devices/{device}', 'DeviceController@destroy');
    Route::get('events/{event}/scheduled', 'EventController@scheduledDeliveriesIndex');
    Route::get('scheduled/{scheduled_delivery}', 'EventController@scheduledDelivery');
    Route::delete('scheduled/{scheduled_delivery}', 'EventController@scheduledDeliveriesDestroy');

    Route::get('home', 'EventController@home');

    Route::get('events/{event}/emails', 'EventController@emails');

    Route::get('events/{event}/collectors', 'EventController@collectors');
    Route::post('events/{event}/collectors', 'EventController@syncCollectors');

    Route::get('participations-data/{email}', 'ParticipantController@participationsWithEvent');
    Route::get('participants/{participant}/export', 'ParticipantController@export');
    Route::post('participations/register', 'ParticipantController@register');

    Route::apiResource('polls', 'PollController');

    Route::apiResource('orders', 'OrderController');
    Route::post('orders/{order}/pay', 'OrderController@pay');

    Route::apiResource('estimates', 'EstimateController');

    Route::apiResource('discounts', 'DiscountController');

    Route::get('tickets', 'TicketController@companyIndex');
    Route::get('tickets/{ticket}/discounts', 'TicketController@ticketDiscounts');
    Route::get('ticketsByEvent/{id}', 'TicketController@ticketsByEvent');

    Route::get('communes', 'DataController@communes');
    Route::get('regions', 'DataController@regions');

    ####
    ## Email Contructor
    ####

    # Templates
    Route::apiResource('templates', 'TemplateController');
    Route::patch('templates/{template}/editName', 'TemplateController@updateName');
    Route::post('templates/{template}/testEmail', 'TemplateController@testEmail');
    Route::post('events/{event}/template', 'EventController@storeEmailTemplate');
    // Route::put('events/{event}/template/{template}', 'EventController@updateEmailTemplate');
    // Route::patch('events/{event}/template/{template}', 'EventController@updateEmailTemplate');
    Route::patch('events/{event}/templates/default', 'EventController@updateDefaultTemplate');

    ####
    ## Uploads
    ####

    # Image Uploadas
    Route::post('uploadLogo', 'UploadController@uploadLogo');
    Route::post('uploadPhoto', 'UploadController@uploadPhoto');
    Route::post('uploadLogoSponsor', 'UploadController@uploadLogoSponsor');
    Route::post('uploadLogoEvent', 'UploadController@uploadLogoEvent');
    Route::post('uploadBannerEvent', 'UploadController@uploadBannerEvent');
    Route::post('uploadLandingImages', 'UploadController@uploadLandingImages');
    Route::post('uploadLocationEvent', 'UploadController@uploadLocationEvent');
    Route::post('uploadTemplateImage', 'UploadController@uploadTemplateImage');

    # Excel Uploads
    Route::post('databases/{database}/uploadDatabase', 'UploadController@uploadDatabase');
    Route::post('events/{event}/uploadActivities', 'UploadController@uploadActivities');
    Route::post('events/{event}/uploadActors', 'UploadController@uploadActors');
    Route::post('discounts/uploadKeyValues', 'UploadController@uploadKeys');

    ####
    ## Constants
    ####

    Route::get('constants/{constant}', 'ConstantController@show');

    ## APP
    Route::get('users/{user}/events', 'UserController@collectorEvents');
    Route::get('events/{event}/participantsForApp', 'EventController@participantsForApp');
    Route::get('events/{event}/databaseForApp', 'DatabaseController@databaseForApp');
    Route::post('events/{event}/participants/{participant}/confirmsFromApp', 'ParticipantController@confirmsFromApp');
    Route::post('participations/registerFromApp', 'ParticipantController@registerFromApp');
    Route::post('events/{event}/fullFromApp', 'EventController@fullFromApp');

    ## Control Panel
    Route::get('events/{event}/participants', 'EventController@participantsForControlPanel');
    Route::post('participations/registerFromControlPanel', 'EventController@registerFromControlPanel');
    Route::post('databases/{database}/participants/storeFromControlPanel', 'ParticipantController@storeFromControlPanel');

    ## Control Panel && APP
    Route::post('events/{event}/participation/checkPrints', 'EventController@checkPrints');
    Route::post('events/{event}/participation/queuePrint', 'EventController@queuePrint');

    ## WEB CHAT
    Route::get('events/{event}/streamChatMessages', 'EventController@streamChatMessages');
    Route::post('events/{event}/streamChatMessages', 'EventController@sendStreamChatMessages');
    Route::post('events/{event}/questionRequest', 'EventController@questionRequest');
    Route::get('events/{event}/questionVotes', 'EventController@questionVotes');
    Route::get('events/{event}/submittedQuestions', 'EventController@submittedQuestions');
    Route::post('events/{event}/questions/{question}', 'StreamingController@sendPollQuestion');
    Route::get('events/{event}/answers', 'StreamingController@getAnswers');

    ## Reports
    Route::get('events/{event}/dashboard/export', 'EventController@dashboardExport');

    ## WEBPAY
    Route::post('events/{event}/webpay/payment', 'WebpayController@payment');

    ## Mail verification
    Route::post('databases/{database}/verify', 'DatabaseController@verifyEmails');

    ## Streamer controller router
    Route::get('events/{event}/activeInChat', 'StreamingController@activeParticipants');

    # Stand Meetings
    Route::get('stands/{stand}/meetings', 'StandController@meetings');
    Route::get('stands/meetings/{meeting}', 'StandController@meeting');
    Route::match(['put', 'patch'], 'stands/meetings/{meeting}', 'StandController@updateMeeting');
    Route::match(['put', 'patch'], 'stands/meetings', 'StandController@updateMeetings');


    ## Stands
    Route::post('events/{event}/stands', 'StandController@store');
    Route::get('events/{event}/stands', 'StandController@index');
    Route::match(['put', 'patch'], 'stands/{stand}', 'StandController@update');
    Route::get('stands/{stand}', 'StandController@show');
    Route::delete('stands/{stand}', 'StandController@destroy');
    Route::post('uploadStandFile', 'UploadController@uploadStandFile');

    ## Stand Manager
    Route::get('events/{event}/managers', 'StandManagerController@index');
    Route::post('stands/{stand}/manager', 'StandManagerController@store');
    Route::match(['put', 'patch'], 'managers/{standManager}', 'StandManagerController@update');
    Route::get('managers/{standManager}', 'StandManagerController@show');
    Route::delete('managers/{standManager}', 'StandManagerController@destroy');

    ## User Webinars
    Route::get('webinars', 'WebinarController@getCompanyWebinars');
    Route::get('webinars/data', 'WebinarController@getWebinarData');
    Route::post('webinars', 'WebinarController@storeCompanyWebinar');
    Route::post('webinars/user', 'WebinarController@store');
    Route::patch('webinars/{event}/user', 'WebinarController@update');
    Route::patch('webinars/user/landing', 'WebinarController@updateLanding');
    Route::post('webinars/{event}/participantMail', 'WebinarController@sendParticipantMail');
    Route::post('webinars/{event}/speakerMail', 'WebinarController@sendSpeakerMail');
    Route::post('webinars/{event}/actors', 'WebinarController@syncActors');
    Route::post('webinars/{event}/sponsors', 'WebinarController@syncSponsors');
    Route::post('webinars/{event}/polls', 'WebinarController@syncPolls');
    Route::post('webinars/{event}/reminder', 'WebinarController@reminder');
    Route::post('webinars/{event}/confirmation-email', 'WebinarController@confirmationEmail');
    Route::post('webinars/{event}/qr-email', 'WebinarController@qrEmail');
});

Route::post('uploadFormImage', 'UploadController@uploadFormImage');
Route::post('uploadFormPDF', 'UploadController@uploadFormPDF');

Route::post('events/{event}/checkEmail', 'PollController@checkEmail');

# Webinar User
Route::get('webinars/user', 'WebinarController@user');

# Woocommerce Hook For Accounts


Route::get('events/{event}/exportSimpleConfirmation', 'EventController@exportSimpleConfirmation');
Route::get('events/{event}/exportSimpleSales', 'EventController@exportSimpleSales');
Route::get('events/{event}/exportSimpleConsolidated', 'EventController@exportSimpleConsolidated');

# DEV
Route::post('vonage/{SESSION_ID}/mute', 'WebinarController@muteVonageSession');
