<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Medico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConsultaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $consultas = Consulta::with(['paciente.usuario', 'medico.usuario'])->paginate();

        return view('consulta.index', compact('consultas'))
            ->with('i', ($request->input('page', 1) - 1) * $consultas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $consulta = new Consulta();

        return view('consulta.create', compact('consulta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConsultaRequest $request): RedirectResponse
    {
        // 1. Validación de los datos (ajusta según tus necesidades)
        $request->validate([
            'paciente_id' => 'required',
            'medico_id'   => 'required',
            'cita_id'     => 'required',
            'sintomas'    => 'required',
            'diagnostico' => 'required',
            'prescripcion'=> 'required',
            'peso'        => 'nullable|numeric',
            'temperatura' => 'nullable|numeric',
        ]);

        // 2. Guardar la consulta en la base de datos
        $consulta = \App\Models\Consulta::create($request->all());

        // 3. LOGICA CLAVE: Actualizar el estado de la cita
        // Buscamos la cita y le cambiamos el estado a 'Finalizada' (o el nombre que uses)
        $cita = \App\Models\Cita::find($request->cita_id);
        if ($cita) {
            $cita->estado = 'asistida'; // Asegúrate que este estado exista en tu lógica
            $cita->save();
        }

        // 4. Redireccionar con un mensaje de éxito (Levi podrá ponerle SweetAlert2 aquí)
        return redirect()->route('cita.index')
            ->with('success', 'La consulta ha sido guardada y la cita se marcó como finalizada.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $consulta = Consulta::with(['paciente.usuario', 'medico.usuario', 'cita'])->findOrFail($id);

        return view('consulta.show', compact('consulta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // Buscamos la consulta con todas sus relaciones
        $consulta = Consulta::with(['paciente.usuario', 'medico.usuario', 'cita'])->findOrFail($id);
        
        // Recuperamos la cita específica para llenar el encabezado de "Datos de la Atención"
        $cita = $consulta->cita;

        // Traemos los antecedentes para las alertas de colores
        $enfermedades = $cita->paciente->enfermedades; 
        $alergias = $cita->paciente->alergias; 

        return view('consulta.edit', compact('consulta', 'cita', 'enfermedades', 'alergias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // 1. Validamos los datos
        $request->validate([
            'sintomas'    => 'required',
            'diagnostico' => 'required',
            'prescripcion'=> 'required',
        ]);

        // 2. Buscamos la consulta manualmente por el ID para asegurar que editamos la misma
        $consulta_existente = \App\Models\Consulta::findOrFail($id);

        // 3. Actualizamos el registro existente
        $consulta_existente->update($request->all());

        // 4. Redireccionamos
        return redirect()->route('consulta.index')
            ->with('success', 'La consulta ha sido actualizada correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        Consulta::find($id)->delete();

        return Redirect::route('consulta.index')
            ->with('success', 'Consulta deleted successfully');
    }

    public function atender($cita_id)
    {
        // 1. Cargamos la cita con el paciente y el médico para tener los nombres listos
        $cita = \App\Models\Cita::with(['paciente.usuario', 'medico.usuario'])->findOrFail($cita_id);

        // 2. Traemos las enfermedades y alergias para que el médico las vea de inmediato
        // Esto es vital para el diseño de expediente que quieres
        $enfermedades = $cita->paciente->enfermedades; 
        $alergias = $cita->paciente->alergias; 

        // 3. Creamos una instancia vacía de Consulta para el formulario
        $consulta = new \App\Models\Consulta();

        // 4. Retornamos la vista 'create' enviando toda la información
        return view('consulta.create', compact('cita', 'enfermedades', 'alergias','consulta'));
    }
}
