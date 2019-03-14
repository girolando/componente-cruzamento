@extends('layout.blank.index')

@section('content')
    <table class="table tooltip-demo table-stripped table-hover comp-tbl-search-cruzamento" data-page-size="10" data-filter=#filter>
        <thead>
        @if(isset($multiple) && $multiple)
            <th>#</th>
        @endif
        <th>Cobrição</th>
        <th>Tipo</th>
        <th>Reprodutor</th>
        <th>Matriz</th>
        <th>Dt. Cruzamento</th>
        @if(!(isset($multiple) && $multiple))
            <th>Selecionar</th>
        @endif
        </thead>
    </table>
@endsection

@section('javascript')
    <script type="text/javascript">

        Componente.scope(function(){ //escopando as variáveis para não conflitarem com possíveis outros componentes do mesmo tipo abertos na tela
            var componente = Componente.CruzamentoFactory.get('{!! $name !!}');

            var colunas = [
                {
                    name: 'numeroComunicacao',
                    data: 'numeroComunicacao'
                },
                {
                    name : 'tipoComunicacao',
                    data : function(obj){
                        var tipo = {
                            1: 'MONTA A CAMPO',
                            2: 'MONTA CONTROLADA',
                            3: 'INSEMINAÇÃO ARTIFICIAL',
                            4: 'FIV',
                            5: 'TE'
                        }
                        return tipo[obj.tipoComunicacao];
                    }
                },
                {
                    name: 'nomeReprodutor',
                    data: function(obj) {
                        return obj.nomeReprodutor + ' - ' + obj.registroReprodutor;
                    }
                },
                {
                    name: 'nomeMatriz',
                    data: function(obj) {
                        return obj.nomeMatriz + ' - ' + obj.registroMatriz;
                    }
                },
                {
                    name: 'dataCruzamento',
                    data: function(obj) {
                        return moment(obj.dataCruzamento).format('D/MM/Y');
                    }
                }
            ];

            @if(isset($multiple) && $multiple)
                colunas.unshift({
                name : '{!! $tableName !!}.id',
                data : function(obj){
                    var idfield = '_compcruzamento_{!! $name !!}_' + obj.id;
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
                    var idfield = '_compcruzamento_{!! $name !!}_' + obj.id;
                    return '<button id="' + idfield + '" class="btn btn-sm btn-primary btnSelecionar" codigo="' + obj.id + '">Selecionar</button>';
                }
            });
            @endif


                    componente.dataTableInstance = $(".comp-tbl-search-cruzamento")
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
                            url : '/vendor-girolando/componentes/cruzamento',
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