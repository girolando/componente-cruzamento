## Componente Cruzamento

### Utilização

  ```html
    {!! ComponenteCruzamento::init() !!} <!-- IMPORTANTE -->
    
    <button class="btnBusca">Buscar Cruzamento</button>
    <componente type="cruzamento" name="codigoCruzamento" dispatcher-button=".btnBusca" />
    
    <script>
      const componente = Componente.CruzamentoFactory.get('codigoCruzamento');
      componente.addEventListener(Componente.EVENTS.ON_FINISH, function(cruzamento) {
        console.log('O cruzamento selecionado foi: ', cruzamento);
      });
    </script>
  ```
  
  ### Método findBy

  ```javascript
    const componente = Componente.CruzamentoFactory.get('nome-do-seu-componente');
    const cruzamentoEspecifico = await componente.findBy({codigoComunicacao: 198564}); 
  ```

### Método getListItems
Esse método refaz a busca que o modal exibiu dentro do datatable. Exemplo:
 - Usuário abriu o componente e pesquisou por cruzamentos oriundos da comunicação número 3214
 - Clicou no botão filtrar e a datatable exibiu 17 resultados
 - O usuário então selecionou um dos cruzamentos, fechando portanto o modal.
 - O programador pretende exibir os mesmos 17 cruzamentos agora em algum lugar, mesmo após o modal ter sido fechado


  ```javascript
    let page = 0;
    let perPage = 10;
    const itensDoModal = componente.getListItems(page, perPage); //obtem os primeiros 10 ítens
    datatable.data(itensDoModal);
    $(".datatable").on('page.dt', function() {
      datatable.data(await componente.getListItems(page * perPage, perPage)).draw();
    });
  ```
