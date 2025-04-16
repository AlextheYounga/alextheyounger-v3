<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
