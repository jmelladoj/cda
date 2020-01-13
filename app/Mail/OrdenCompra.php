<?php

namespace App\Mail;

use App\OrdenCompra as AppOrdenCompra;
use App\Proveedor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrdenCompra extends Mailable
{
    use Queueable, SerializesModels;

    public $orden_compra;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Proveedor $proveedor, AppOrdenCompra $orden_compra)
    {
        //
        $this->proveedor = $proveedor;
        $this->orden_compra = $orden_compra;
        $this->pdf = PDF::loadView('pdf/orden_compra', ['orden_compra' => $orden_compra ])->setPaper('a4')->output();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(Auth::user()->email)
                    ->subject($this->orden_compra->asunto)
                    ->view('email.orden_compra')
                    ->attachData($this->pdf, 'OrdenDeCompra.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
