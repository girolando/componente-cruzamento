@section('ComponentJavascript')
    @parent
    <script type="text/javascript">
        (function(){
            if(Componente.Cruzamento) return; //Preventing this script to load more than once.

            //aki é certeza que o namespace Componente existe, portanto:
            Componente.Cruzamento = function(name, attributes){
                this.attributes         = attributes;
                this.name               = name;
                this.dataTableInstance  = null;
                this.urlFindBy          = '/vendor-girolando/componentes/cruzamento-findby';
                var self                = this;

                /*** Lógica de construtor do componente ****/

                this.getListItems       = () => null;


                /**** Lógica customizada do componente ******/
                /**
                 * Esse é o método principal do componente. Ele é disparado quando a pessoa clicar no Search Button e não for impedida por algum evento.
                 */
                this.onSearchButtonClick = function(){
                    System.beginLoading($("body"), '{!! trans('ComponenteCruzamento::Services/Componentes/ComponentService._init.msgBuscando') !!}');
                    self.selectedItems.clear();
                    $.get('/vendor-girolando/componentes/cruzamento', this.getAttributes(), function(response){
                        System.stopLoading();
                        self.modalInstance = Alert.bigConfirm(response, function(ok){
                            if(!ok){
                                self.modalInstance.modal('hide');
                                self.triggerEvent(Componente.EVENTS.ON_FINISH, null);
                                return;
                            }
                            //se chegou aqui é pq é multiple. No single o botão de ok não aparece
                            if(!self.isUsingQuery){
                                self.modalInstance.modal('hide');
                                self.triggerEvent(Componente.EVENTS.ON_FINISH, self.selectedItems.values());
                                return;
                            }
                        }, '{!! trans('ComponenteCruzamento::Services/Componentes/ComponentService._init.titModal') !!}');


                        if(!self.getAttributes().multiple){
                            $("[data-bb-handler=confirm]", self.modalInstance).hide();
                        }

                    }).fail(function(){
                        System.stopLoading();
                        Alert.error('{!! trans('ComponenteCruzamento::Services/Componentes/ComponentService._init.errOpenModal') !!}');
                    });
                }

            };

            Componente.CruzamentoFactory = Componente.newFactory({
                initialize : function(uniqueItem){
                    var self = this;
                    $("componente[type=cruzamento]").each(function(){
                        self._initialize($(this), Componente.Cruzamento); //método do pai, adicionado pelo newFactory, inicializa o componente
                    })
                }
            });

            Componente.CruzamentoFactory.initialize();
        })();
    </script>
@endsection