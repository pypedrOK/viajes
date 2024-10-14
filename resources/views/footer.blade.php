<script>
    $(document).ready(function(){
        $('#origen').select2({
            placeholder:"Origen",
            allowClear:true,
            ajax:{
                url:function(params){
                    $('#destino').val(null).trigger('change');
                    return "{{ route('city.index') }}";
                },
                dataType:'json',
                method:'GET',
                success:function(res){

                },
                error:function(res){

                }                  
            },
            language:{
                searching: function(){
                    return 'Cargando...';
                },
                noResults: function(){
                    return 'Sin resultados...';
                }
            }
        });
        $('#destino').select2({
            placeholder:"Destino",
            allowClear:true,
            ajax:{
                url:function(params){
                    let ori = $('#origen').val();
                    if(ori){
                        return `/api/ajax/getDestinations/${ori}`;
                    }
                },
                dataType:'json',
                method:'GET',
                success:function(res){

                },
                error:function(res){

                }                  
            },
            language:{
                searching: function(){
                    return 'Cargando...';
                },
                noResults: function(){
                    return 'Sin resultados...';
                }
            }
        })
    });
</script>
@if ($errors->any())
    <script>
        Swal.fire({
            position:'center-center',
            title: '{{ $errors->first() }}',
            icon: 'error',
            ShowConfirmButton:true,
            timer:4500 
        })
    </script>

@endif

@if (session('mensaje'))
    <script>
        Swal.fire({
            position:'center-center',
            title: "{{ session('mensaje') }}",
            icon: 'success',
            ShowConfirmButton:true,
            timer:2500 
        })
    </script>

@endif

<script src="https://cdn.jsdelivr.net/npm/micromodal/dist/micromodal.min.js"></script>
<script>
    //inicialización de micromodal
    document.addEventListener('DOMContentLoaded', function(){
        MicroModal.init();
    });

    function showDetails(id_trip, fecha, hora, disponibles, ocupados, mascotas, fumar, salida, llegada, detalle, foto) {
        $('#modal_id_trip').val(id_trip);
        $('#txt_date').text(fecha);
        $('#txt_details').text(detalle);
        $('#txt_time').text(hora);
        $('#txt_available').text(disponibles);
        $('#modal_seats').attr('max',disponibles);
        $('#txt_occupied').text(ocupados);
        $('#txt_pets').text(mascotas);
        $('#txt_smooking').text(fumar);
        $('#txt_pickup').text(salida);
        $('#txt_dropoff').text(llegada);
        $('#img_driver').attr('src',foto);

        
    }
    
</script>
</body>
</html>