{!! ComponenteAnimal::init() !!}
@extends('layout.blank.index')

@section('content')
    <script src="/bower_components/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
    <script src="/bower_components/twitter-bootstrap-wizard/prettify.js"></script>

    <div class="container-componentecruzamento-{!! $name !!}">
        <div class="rootwizard">
            <div class="row">
                <div class="navbar navbar-inner">
                    <div class="container-fluid">
                        <ul class="nav nav-tabs">
                            <li class="active"><a class="filtros" href="#filtros" data-toggle="tab">Filtros</a></li>
                            <li><a class="resultados" href="#resultados" data-toggle="tab">Resultados</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                <div class="tab-pane active" id="filtros">
                    <div class="form-group col-md-12">
                        <div class="col-md-6">
                            <label class="control-label">
                                <input type="radio" name="tipofiltro" value="cdc"> Filtrar pela CDC ou nr. Formulário
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label">
                                <input type="radio" name="tipofiltro" value="animal"> Filtrar pelo Reprodutor ou Matriz
                            </label>
                        </div>
                    </div>

                    <div class="form-group col-md-12 filtro-cdc hide form form-horizontal">
                        <label class="col-md-2 control-label">Código da CDC:</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control codigoComunicacao">
                        </div>

                        <label class="col-md-2 control-label">Nr. Formulário:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control numeroFormulario">
                        </div>
                        <button class="btn btn-filtrar btn-primary col-2">
                            Filtrar
                        </button>
                    </div>

                    <div class="row filtro-animal hide">

                        <div class="form-group col-md-12 form form-horizontal">
                            <label class="col-md-2 control-label">Reprodutor:</label>
                            <div class="col-md-3">
                                <select class="form-control sangueReprodutor">
                                    <option value="-1">COMP. RACIAL</option>
                                    @foreach($sangues as $sangue)
                                        <option value="{!! $sangue->codigoTipoSangue !!}">{!! $sangue->descTipoSangue !!} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="text" placeholder="Registro" class="form-control registroReprodutor">
                            </div>
                            <div class="col-md-3">
                                <input type="text" placeholder="Nome" class="form-control nomeReprodutor">
                            </div>
                            <div class="col-2 btn-group">
                                <button disabled class="btn btn-limparReprodutor btn-white" data-toggle="tooltip" data-original-title="Limpar Reprodutor">
                                    <i class="fa fa-eraser"></i>
                                </button>
                                <button class="btn btn-pesquisarTouro btn-primary" data-toggle="tooltip" data-original-title="Pesquisar">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <componente type="animal" dispatcher-button=".btn-pesquisarTouro" filter-sexoAnimal="M" name="codigoReprodutor-comp{!! $name !!}"></componente>
                        </div>

                        <div class="form-group col-md-12 form form-horizontal">
                            <label class="col-md-2 control-label">Matriz:</label>
                            <div class="col-md-3">
                                    <select class="form-control sangueMatriz">
                                        <option value="-1">COMP. RACIAL</option>
                                        @foreach($sangues as $sangue)
                                            <option value="{!! $sangue->codigoTipoSangue !!}">{!! $sangue->descTipoSangue !!} </option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="col-md-2">
                                <input type="text" placeholder="Registro" class="form-control registroMatriz">
                            </div>
                            <div class="col-md-3">
                                <input type="text" placeholder="Nome" class="form-control nomeMatriz">
                            </div>

                            <div class="col-2 btn-group">
                                <button disabled class="btn btn-limparMatriz btn-white" data-toggle="tooltip" data-original-title="Limpar Matriz">
                                    <i class="fa fa-eraser"></i>
                                </button>
                                <button class="btn btn-pesquisarMatriz btn-primary" data-toggle="tooltip" data-original-title="Pesquisar">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <componente type="animal" dispatcher-button=".btn-pesquisarMatriz" filter-sexoAnimal="F" name="codigoMatriz-comp{!! $name !!}"></componente>
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <button class="btn btn-filtrar btn-primary">
                                Filtrar
                            </button>
                        </div>
                    </div>


                </div>

                <div class="tab-pane" id="resultados">

                    <div class="col-md-12">
                        <button class="btn btn-white btn-voltar">
                            Voltar aos filtros
                        </button>
                    </div>

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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">

        Componente.filtrado = false;

        Componente.scope(function(){ //escopando as variáveis para não conflitarem com possíveis outros componentes do mesmo tipo abertos na tela
            let container = $(".container-componentecruzamento-{!! $name !!}");

            let novosFiltros = [];
            let tipoFiltro = '';
            @if($_attrFilters)
                @foreach($_attrFilters as $attr => $val)
                    novosFiltros['{!! $attr !!}'] = '{!! $val !!}';
                @endforeach
            @endif


            let compMatriz = Componente.AnimalFactory.get('codigoMatriz-comp{!! $name !!}');
            let compReprodutor = Componente.AnimalFactory.get('codigoReprodutor-comp{!! $name !!}');
            let matrizSelecionada = null;
            let reprodutorSelecionado = null;

            const renderizarAnimal = function(animal, posicao) {
                if (!(posicao === 'matriz' || posicao === 'reprodutor')) throw new Error('Posição deve ser: matriz ou reprodutor');
                const $fieldSangue      = $((posicao === 'matriz') ? ".sangueMatriz" : ".sangueReprodutor", container);
                const $fieldRegistro    = $((posicao === 'matriz') ? ".registroMatriz" : ".registroReprodutor", container);
                const $fieldNome        = $((posicao === 'matriz') ? ".nomeMatriz" : ".nomeReprodutor", container);
                const $btnLimpar        = $((posicao === 'matriz') ? ".btn-limparMatriz" : ".btn-limparReprodutor", container);

                console.log('renderizando animal', animal, posicao, $fieldNome);

                if (posicao === 'matriz') {
                    matrizSelecionada = animal;
                } else {
                    reprodutorSelecionado = animal;
                }

                if (!animal) {
                    $fieldSangue.attr('disabled', false).val(-1);
                    $fieldNome.attr('readonly', false).val('');
                    $fieldRegistro.attr('readonly', false).val('');
                    $btnLimpar.attr('disabled', true);
                    return;
                }
                $fieldSangue.attr('disabled', true).val(animal.codigoTipoSangue);
                $fieldNome.attr('readonly', true).val(animal.nomeAnimal);
                $fieldRegistro.attr('readonly', true).val(animal.registroAnimal);
                $btnLimpar.attr('disabled', false);
            }

            $(".btn-limparMatriz, .btn-limparReprodutor", container).on('click', function() {
                if ($(this).hasClass('btn-limparMatriz')) {
                    return renderizarAnimal(null, 'matriz');
                }
                renderizarAnimal(null, 'reprodutor');
            });

            $('.btn-filtrar', container).on('click', function() {
                novosFiltros = [];
                if ($('[name=tipofiltro]:checked', container).val() == 'cdc') {
                    if ($('.codigoComunicacao', container).val()) {
                        novosFiltros['numeroComunicacao'] = $('.codigoComunicacao', container).val();
                        Componente.codigoComunicacaoDigitada = $('.codigoComunicacao', container).val();
                    }

                    if ($('.numeroFormulario', container).val()) {
                        novosFiltros['codigoAntigoComunicacao'] = $('.numeroFormulario', container).val();
                        Componente.numeroFormularioDigitado = $('.numeroFormulario', container).val();
                    }
                } else {
                    if (matrizSelecionada) novosFiltros['codigoMatriz'] = matrizSelecionada.id;
                    if (reprodutorSelecionado) novosFiltros['codigoReprodutor'] = reprodutorSelecionado.id;
                }
                componente.dataTableInstance.draw();
            });

            $(".registroReprodutor, .registroMatriz, .nomeReprodutor, .nomeMatriz", container).on('change', async function() {
                const $fieldRegistro    = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? $(".registroMatriz", container) : $(".registroReprodutor", container);
                const $fieldSangue      = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? $(".sangueMatriz", container) : $(".sangueReprodutor", container);
                const $fieldNome        = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? $(".nomeMatriz", container) : $(".nomeReprodutor", container);
                const indexCodigo       = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? 'codigoMatriz' : 'codigoReprodutor';
                const sexoEsperado      = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? 'F' : 'M';
                const componenteAnimal  = ($(this).hasClass('registroMatriz') || $(this).hasClass('nomeMatriz')) ? compMatriz : compReprodutor;
                const isBuscaRegistro   = ($(this).hasClass('registroMatriz') || $(this).hasClass('registroReprodutor'));
                const objBusca          = {};
                const $btnLimpar        = ($(this).hasClass('registroMatriz')) ? $(".btnLimparMatriz", container) : $(".btnLimparReprodutor", container);

                if ($fieldSangue.val() > -1) {
                    objBusca.codigoTipoSangue = $fieldSangue.val();
                }
                if ($fieldRegistro.val()) {
                    const registroLimpoDigitado = $fieldRegistro.val().replace(/\-/g, '').replace(/\s/g);
                    objBusca.registroLimpoAnimal = registroLimpoDigitado;
                }
                if ($fieldNome.val()) {
                    objBusca.nomeAnimal = $fieldNome.val();
                }
                objBusca.sexoAnimal = sexoEsperado;
                System.beginLoading();
                const animais = await componenteAnimal.findBy(objBusca);
                System.stopLoading();
                $fieldNome.val('');

                $btnLimpar.attr('disabled', true);


                renderizarAnimal(null, (sexoEsperado) === 'F' ? 'matriz' : 'reprodutor');
                if (animais.length === 1 && animais[0].sexoAnimal === sexoEsperado) {
                    renderizarAnimal(animais[0], (sexoEsperado) === 'F' ? 'matriz' : 'reprodutor');
                    reprodutorSelecionado = null;
                    matrizSelecionada = null;
                    
                    if (sexoEsperado === 'M') {
                        reprodutorSelecionado = animais[0];
                    } else {
                        matrizSelecionada = animais[0];
                    }
                    $btnLimpar.attr('disabled', false);
                    console.log('chegou aki', sexoEsperado, reprodutorSelecionado, matrizSelecionada);
                } 
            });

            compMatriz.addEventListener(Componente.EVENTS.ON_FINISH, function(animal) {
                if (!animal) return;
                $(".nomeMatriz", container).val(animal.nomeAnimal);
                $(".registroMatriz", container).val(animal.registro);
                $(".sangueMatriz", container).val(animal.codigoTipoSangue);
                matrizSelecionada = animal;
                renderizarAnimal(animal, 'matriz');
                componente.dataTableInstance.draw();
            });

            compReprodutor.addEventListener(Componente.EVENTS.ON_FINISH, function(animal) {
                if (!animal) return;
                $(".nomeReprodutor", container).val(animal.nomeAnimal);
                $(".registroReprodutor", container).val(animal.registro);
                $(".sangueReprodutor", container).val(animal.codigoTipoSangue);
                reprodutorSelecionado = animal;
                renderizarAnimal(animal, 'reprodutor');
                componente.dataTableInstance.draw();
            });


            $('[name=tipofiltro]', container).on('change', function() {
                let self = $(this);

                $('.filtro-cdc', container).addClass('hide');
                $('.filtro-animal', container).addClass('hide');
                tipoFiltro = self.val();
                if (self.val() == 'cdc') {
                    return $('.filtro-cdc', container).removeClass('hide');
                }
                if (self.val() == 'animal') {
                    return $('.filtro-animal', container).removeClass('hide');
                }
            });

            let wizard = $('.rootwizard', container).bootstrapWizard({
                previousSelector: $(".btn-voltar", container),
                nextSelector: $(".btn-filtrar", container),
                onTabClick: function (tab, navigation, index) {
                    console.log('clicou na aba: ', tab, navigation, index);
                    return true;
                }
            });

            var componente = Componente.CruzamentoFactory.get('{!! $name !!}');



            if (Componente.codigoComunicacaoDigitada || Componente.numeroFormularioDigitado) {
                $('[name=tipofiltro][value=cdc]', container).attr('checked', true).trigger('change');
                if (Componente.codigoComunicacaoDigitada) {
                    $('.codigoComunicacao', container).val(Componente.codigoComunicacaoDigitada);
                }
                if (Componente.numeroFormularioDigitado) {
                    $('.numeroFormulario', container).val(Componente.numeroFormularioDigitado);
                }
                $('.btn-filtrar', container).trigger('click');
            }


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
                    if (obj.cruzamentoEmAguardo) {
                        return '<span class="label label-danger" data-toggle="tooltip" data-original-title="Cruzamento foi transferido porém ainda não foi autorizado">Bloqueado</span>'
                    }
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
                    if (obj.cruzamentoEmAguardo) {
                        return '<span class="label label-danger" data-toggle="tooltip" data-original-title="Cruzamento foi transferido porém ainda não foi autorizado">Bloqueado</span>'
                    }
                    var idfield = '_compcruzamento_{!! $name !!}_' + obj.id;
                    return '<button id="' + idfield + '" class="btn btn-sm btn-primary btnSelecionar" codigo="' + obj.id + '">Selecionar</button>';
                }
            });
            @endif


                    componente.dataTableInstance = $(".comp-tbl-search-cruzamento", container)
                    .on('xhr.dt', function(){
                        setTimeout(function(){
                            $("[data-toggle=tooltip]", container).tooltip();
                        }, 0);
                    })
                    .CustomDataTable({
                        name : '_dataTableQuery{!! $name !!}',
                        queryParams : {
                            idField : '{!! $tableName !!}.id',
                            filtersCallback : function(obj){

                                console.log('verificando novosfiltros: ', novosFiltros);
                                if (novosFiltros) {
                                    delete obj.id;
                                    delete obj.numeroComunicacao;
                                    delete obj.codigoMatriz;
                                    delete obj.codigoReprodutor;
                                    console.log(novosFiltros);
                                    console.log('filtros antigos: ', obj);
                                    console.log('tipoFiltro =>>>', tipoFiltro);
                                    for(let prop in novosFiltros) {
                                        if (prop == 'numeroComunicacao' && tipoFiltro == 'animal') continue;
                                        if (prop == 'codigoMatriz' && tipoFiltro == 'cdc') continue;
                                        if (prop == 'codigoReprodutor' && tipoFiltro == 'cdc') continue;
                                        console.log('adicionando filtro: ', novosFiltros[prop], prop);
                                        obj[prop] = novosFiltros[prop];
                                    }
                                    console.log('filtros trataodos =>>> ', obj);
                                } else {
                                    console.log('estou adicionando -1 pq novosfiltros eh: ', novosFiltros);
                                    obj.id = -1;
                                }
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
                    componente.getListItems         = (offset, limit) => {
                        let url = componente.dataTableInstance.data().ajax.url();
                        url += '?';
                        let params = componente.dataTableInstance.data().ajax.params();
                        params.length = limit;
                        params.start = offset;
                        url += jQuery.param(params);
                        return new Promise((resolve, reject) => {
                            $.get(url, function(response) {
                                resolve(response.data);
                            });
                        });
                    };
                    componente.triggerEvent(Componente.EVENTS.ON_FINISH, entity);
                });
            @endif
        });
    </script>
    <style type="text/css">
        .container-componentecruzamento-{!! $name !!} input[type=number]::-webkit-inner-spin-button, 
        .container-componentecruzamento-{!! $name !!} input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
        }
    </style>
@endsection