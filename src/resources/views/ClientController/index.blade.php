@extends('layout.blank.index')

@section('content')
    <table class="table tooltip-demo table-stripped table-hover comp-tbl-search-pessoa" data-page-size="10" data-filter=#filter>
        <thead>
        @if(isset($multiple) && $multiple)
            <th>#</th>
        @endif
        <th>Código</th>
        <th>Nome</th>
        <th>Tipo</th>
        @if(!(isset($multiple) && $multiple))
            <th>Selecionar</th>
        @endif
        </thead>
    </table>
@endsection

@section('javascript')
    <script type="text/javascript">

        Componente.scope(function(){ //escopando as variáveis para não conflitarem com possíveis outros componentes do mesmo tipo abertos na tela
            var componente = Componente.PessoaFactory.get('{!! $name !!}');

            var colunas = [
                {
                    name: 'codCfoCriador',
                    data : function(obj){
                        if(obj.codCfoCriador) return obj.codCfoCriador;
                        if(obj.crmvVeterinario) return obj.crmvVeterinario;
                        return '-';
                    }
                },
                {
                    name : 'nomePessoa',
                    data : function(obj){
                        return '<label for="_comppessoa_{!! $name !!}_' + obj.id + '">' + obj.nomePessoa + '</label>';
                    }
                },
                {name : 'isCriadorAtivo', searchable: false, orderable: false, data : function(obj){
                    var tipo = '';

                    if(obj.isCriadorAtivo == 1) {
                        tipo += '<span data-toggle="tooltip" data-placement="top" data-original-title="{!! trans('ComponentePessoa::ClientController.tipos.associadoAtivo') !!}" class="label label-success">{!! trans('ComponentePessoa::ClientController.tipos.sgAssociadoAtivo') !!}</span> ' ;
                    }

                    if(obj.idTipoCriador == 'ASSOC' && !obj.statusCriador) {
                        tipo += '<span data-toggle="tooltip" data-placement="top" data-original-title="{!! trans('ComponentePessoa::ClientController.tipos.associadoInativo') !!}" class="label label-danger">{!! trans('ComponentePessoa::ClientController.tipos.sgAssociadoInativo') !!}</span> ';
                    }

                    if(obj.isFuncionario == 1){
                        tipo += '<span data-toggle="tooltip" data-placement="top" data-original-title="{!! trans('ComponentePessoa::ClientController.tipos.funcionario') !!}" class="label label-primary">{!! trans('ComponentePessoa::ClientController.tipos.sgFuncionario') !!}</span> ';
                    }

                    if(obj.isTecnico == 1){
                        tipo += '<span data-toggle="tooltip" data-placement="top" data-original-title="{!! trans('ComponentePessoa::ClientController.tipos.tecnico') !!}" class="label label-warning">{!! trans('ComponentePessoa::ClientController.tipos.sgTecnico') !!}</span> ';
                    }

                    if(obj.isVeterinario == 1){
                        tipo += '<span data-toggle="tooltip" data-placement="top" data-original-title="{!! trans('ComponentePessoa::ClientController.tipos.veterinario') !!}" class="label label-info">{!! trans('ComponentePessoa::ClientController.tipos.sgVeterinario') !!}</span> ';
                    }
                    return tipo;
                }}
            ];

            @if(isset($multiple) && $multiple)
                colunas.unshift({
                name : '{!! $tableName !!}.id',
                data : function(obj){
                    var idfield = '_comppessoa_{!! $name !!}_' + obj.id;
                    if(componente.dataTableInstance.DataTableQuery().isItemChecked(obj.id)) {
                        return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionar" type="checkbox" checked="checked" value="' + obj.id + '">';
                    }
                    return '<input id="' + idfield + '" class="checkbox checkbox-primary chkSelecionar" type="checkbox" value="' + obj.id + '">';
                }
            });
            @else
                colunas.push({
                name : '{!! $tableName !!}.id',
                data : function(obj){
                    var idfield = '_comppessoa_{!! $name !!}_' + obj.id;
                    return '<button id="' + idfield + '" class="btn btn-sm btn-primary btnSelecionar" codigo="' + obj.id + '">Selecionar</button>';
                }
            });
            @endif


                    componente.dataTableInstance = $(".comp-tbl-search-pessoa")
                    .on('xhr.dt', function(){
                        setTimeout(function(){
                            $("[data-toggle=tooltip]").tooltip();
                        }, 0);
                    })
                    .CustomDataTable({
                        name : '_dataTableQuery{!! $name !!}',
                        queryParams : {
                            idField : '{!! $tableName !!}.id',
                            filtersCallback : function(obj){
                                @if($_attrFilters)
                                        @foreach($_attrFilters as $attr => $val)
                                        obj['{!! $attr !!}'] = '{!! $val !!}';
                                @endforeach
                                @endif
                            }
                        },
                        columns : colunas,
                        ajax : {
                            url : '/vendor-girolando/componentes/pessoa',
                            data : function(obj){
                                obj.name = '{!! $name !!}';
                            }
                        }
                    });


            @if(isset($multiple) && $multiple)
                componente.modalInstance.delegate('.chkSelecionar', 'change', function(){
                    var val = $(this).val();
                    var obj = componente.dataTableInstance.row($(this).closest('tr'));
                    if(!componente.dataTableInstance.DataTableQuery().isChecked(val)){
                        componente.selectedItems.put(val, obj.data());
                        return componente.dataTableInstance.DataTableQuery().addItem(val);
                    }
                    componente.selectedItems.remove(val);
                    return componente.dataTableInstance.DataTableQuery().removeItem(val);
                });
            @else
                componente.modalInstance.delegate('.btnSelecionar', 'click', function(){
                    var entity = componente.dataTableInstance.row($(this).closest('tr')).data();
                    componente.selectedItems.clear();
                    componente.selectedItems.put($(this).attr('codigo'), entity);
                    componente.modalInstance.modal('hide');
                    componente.triggerEvent(Componente.EVENTS.ON_FINISH, entity);
                });
            @endif
        });
    </script>
@endsection