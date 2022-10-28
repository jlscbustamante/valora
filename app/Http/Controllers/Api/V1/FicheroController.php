<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
//use Illuminate\Routing\Controller as BaseController;
use App\Models\Fichero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FicheroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        /*if (!Storage::disk('public')->exists('file4.txt'))
            Storage::disk('public')->put('file4.txt', '');*/
        $ficheros = Fichero::where('status','=',1)->get();
        
        $cont_filas = Fichero::where('status','=',1)->count();
        
        //$filas = count($ficheros);
        
        if ($cont_filas >0 ){
        	return json_encode($ficheros);
        }else{
        	return json_encode([]);
        	//return $response()->json("Lista vacía",200);
        }   
            
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateFichero($request);
        
        $files = [];
        
        //cantidad de archivos recibidos
        //$cant_ficheros  = count($request->file('files'));
        
        //array con los ficheros recibidos
        $arr_ficheros = $request->file('files');
        if ($request->hasFile('files')){
            foreach($arr_ficheros as $key => $file)
            {
                $fileName = time().rand(1,99).'.'.$file->extension();  
                
        		Storage::disk('public')->put('test/'.$fileName, file_get_contents($file));
            		
                $files[$key]['name'] = $fileName;
                $files[$key]['path'] = 'test';
                //Storage::size obtiene el tamaño en bytes
                $files[$key]['length'] = Storage::size('public/test/'.$fileName);
                $files[$key]['status'] = 1;
            }
            
            //crear el elemento en la BD
            
            
            foreach ($files as $key => $file) {
            	//File::create($file);
            	// // Create files
                $fichero = new Fichero();
                $fichero->name = $file['name'];
                $fichero->path = 'test';
                $fichero->length= $file['length']; 
                $fichero->status= 1;
                
                $fichero->save();
                
        	}
        	
        	return response()->json("Se guardó ".count($files)." ficheros.",200);
        	//return response()->json($files,200);
        }else{
        	return response()->json("Ud no ha subido ficheros.",200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fichero  $fichero
     * @return \Illuminate\Http\Response
     */
    public function show(Fichero $fichero)
    {
        //
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Fichero  $fichero
     * @return \Illuminate\Http\Response
     */
    public function update($name)
    {
        $fichero = Fichero::where('name', '=', $name)->firstOrFail();
        
	    // Actualizar el estado del registro del fichero de la BD
	    //para indicar un borrado logico
	    $fichero->status = 2;
	    $fichero->save();
	    
	    $fichero->delete();
	    
	    // Return Json Response
	    return response()->json([
	        'message' => "Se hizo la eliminacion lógica del fichero correctamente."
	    ],200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Fichero  $fichero
     * @return \Illuminate\Http\Response
     */
    //public function destroy(Fichero $fichero)
    public function destroy($name)
    {
    	//devuelve 404 si no es encontrado el registro
        $fichero = Fichero::where('name', '=', $name)->firstOrFail();
        
        $del_name = $fichero->name;
        $del_path = $fichero->path;
        
	    // Delete fichero de la BD
	    $fichero->forceDelete();
	    
	    
	    //if(Storage::exists($fichero->path.'/'.$fichero->name)){
	    if(Storage::exists('public/'.$del_path.'/'.$del_name)){
            Storage::delete('public/'.$del_path.'/'.$del_name);
            /*
                Delete Multiple File like this way
                Storage::delete(['upload/test.png', 'upload/test2.png']);
            */
        }else{
            return response()->json([
	        'message' => "No se pudo eliminar el fichero del SO."
	    	],404);
        }
	
	    // Return Json Response
	    return response()->json([
	        'message' => "Fichero eliminado correctamente."
	    ],204); //204 es SIN CONTENIDO
    }
    
    public function validateFichero(Request $request){
    	
    	//el valor de max es en KB
    	return $request->validate([
    		'files'=>'required',
    		'files.*'=>'required|max:500',
    		]);
    }
}
