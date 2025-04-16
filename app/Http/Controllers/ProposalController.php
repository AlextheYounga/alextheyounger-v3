<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class ProposalController extends Controller
{
    /**
     * Display the specified proposal.
     *
     * @param  string  $hash
     * @return \Inertia\Response
     */
    public function show($hash)
    {
        $proposal = Proposal::where('hash', $hash)->firstOrFail();
        return Inertia::render('Proposal', [
            'proposal' => $proposal
        ]);
    }

    /**
     * Handle the proposal signing.
     *
     * @param  string  $hash
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sign($hash, Request $request)
    {
        $request->validate([
            'signature' => 'required|string|min:3',
            'agreement' => 'required|boolean'
        ]);
		
        $proposal = Proposal::where('hash', $hash)->firstOrFail();

        $proposal->update([
            'client_signature' => $request->signature,
            'client_agreement' => true,
            'client_sign_date' => Carbon::now()
        ]);

        return redirect()->back();
    }
}
