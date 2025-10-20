<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    /**
     * Exibe a lista de leads
     */
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();
        return view('leads.index', compact('leads'));
    }

    /**
     * Mostra o formulário de criação de lead
     */
    public function create()
    {
        return view('leads.create');
    }

    /**
     * Armazena um novo lead no banco de dados
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'notes'  => 'nullable|string',
        ]);

        $lead = Lead::create($validated);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um lead
     */
    public function show($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.show', compact('lead'));
    }

    /**
     * Mostra o formulário de edição de um lead existente
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.edit', compact('lead'));
    }

    /**
     * Atualiza os dados de um lead
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email|max:255',
            'phone'  => 'nullable|string|max:30',
            'source' => 'nullable|string|max:100',
            'status' => 'required|in:novo,em_contato,agendado,convertido,perdido',
            'notes'  => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);
        $lead->update($validated);

        return redirect()->route('leads.index')
                         ->with('success', 'Lead atualizado com sucesso!');
    }

    /**
     * Exclui um lead
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('leads.index')
                         ->with('success', 'Lead excluído com sucesso!');
    }
}
