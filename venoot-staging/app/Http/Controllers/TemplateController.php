<?php

namespace App\Http\Controllers;

use App\Template;
use App\Mail\Direct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['templates' => Auth::user()->company->templates->sortByDesc('created_at')->values()], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'content' => 'nullable|string',
            'test_email' => 'nullable|email:rfc,dns'
        ]);

        $template = Auth::user()->company->templates()->create(['name' => $request->name, 'content' => $request->content, 'test_email' => $request->test_email]);
        $template->save();

        return response()->json(['template' => $template], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'content' => 'string',
        ]);

        $template->content = $request->content;
        $template->save();

        return response()->json(['template' => $template], 200);
    }

    public function updateName(Request $request, Template $template)
    {
        $request->validate([
            'name' => 'required|string|min:3',
            'test_email' => 'nullable|email:rfc,dns'
        ]);

        $template->name = $request->name;
        $template->test_email = $request->test_email;
        $template->save();

        return response()->json(['template' => $template], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        $user = Auth::user();
        if ($user && $user->company == $template->company) {
            $template->delete();
        }
    }

    public function builder(Template $template)
    {
        $user = Auth::user();
        if ($user && $user->company == $template->company) {
            return view('email-builder', ['template' => $template, 'token' => Auth::tokenById($user->id)]);
        }

        return abort(401);
    }

    public function builderTemplate(Template $template)
    {
        return view('template', ['template' => $template]);
    }

    public function testEmail(Template $template)
    {
        $converter = new CssToInlineStyles();
        $content =  $converter->convert($template->content, file_get_contents(__DIR__ . '/bootstrap.min.css'));

        Mail::to($template->test_email)
            ->queue(new Direct($content));

        return response()->json(['email' => $template->test_email], 200);
    }

    public function mailPreview(Template $template)
    {
        $converter = new CssToInlineStyles();
        $content =  $converter->convert($template->content, file_get_contents(__DIR__ . '/bootstrap.min.css'));

        return view('emails.direct', ['body' => $content]);
    }
}
