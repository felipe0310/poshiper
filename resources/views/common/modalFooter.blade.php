</div>
    <div class="modal-footer">
        
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-button btn-danger" data-bs-dismiss="modal">CERRAR</button>

            @if($seleccionar_id < 1) 
            <button type="button" wire:click.prevent="Store()" class="btn btn-info close-modal">
            GUARDAR</button>
            @else        
            <button type="button" wire:click.prevent="Update()" class="btn btn-info close-modal">
            ACTUALIZAR</button>
            @endif

      </div>
    </div>
  </div>
</div>