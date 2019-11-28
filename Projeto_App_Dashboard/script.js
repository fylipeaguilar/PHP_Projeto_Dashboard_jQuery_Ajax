// "$(document).ready(() => {})"
// Executar o script somento após o carregamento do DOM

$(document).ready(() => {
	$('#documentacao').on('click', () => {
		// console.log('link documentacao clicado')

		// ***************** LOAD ************** //
		// load: método para carregar um conteudo
		// $('#pagina').load('documentacao.html')

		// ***************** GET ************** //
		// Sintaxe: $.get('url', () => {})
		// data: usado esse parâmetro que é o parâmetro da documentação
		// $.get('documentacao.html', data => {
		// 	//console.log(data)
		// 	$('#pagina').html(data)
		// })

		// ***************** POST ************** //
		// Sintaxe: $.post('url', () => {})
		// data: usado esse parâmetro que é o parâmetro da documentação
		$.post('documentacao.html', data => {
			//console.log(data)
			$('#pagina').html(data)
		})
	})

	$('#suporte').on('click', () => {
		// console.log('link suporte clicado')

		// ***************** LOAD ************** //
		// load: método para carregar um conteudo
		// $('#pagina').load('suporte.html')

		// ***************** GET ************** //
		// Sintaxe: $.get('url', () => {})
		// data: usado esse parâmetro que é o parâmetro da documentação
		// $.get('suporte.html', data => {
		// 	//console.log(data)
		// 	$('#pagina').html(data)
		// })

		// ***************** POST ************** //
		// Sintaxe: $.post('url', () => {})
		// data: usado esse parâmetro que é o parâmetro da documentação
		$.post('suporte.html', data => {
			//console.log(data)
			$('#pagina').html(data)
		})
	})

	// Implementando com o método Ajax - Requisições HTTP assincronas
	$('#competencia').on('change', e => {

		// Atribuindo o valor da competencia selecionada a uma variavel
		let competencia = $(e.target).val()
		//console.log(competencia)


		// console.log($(e.target).val())
		// O método ajax espera um objeto literal "predefinido"
		// objeto literal: método, url, dados (sim ou não) para o backend, sucesso, erro
		$.ajax({
			type: 'GET',
			url: 'app.php',
			data: `competencia=${competencia}`, //x-www-form-urlencoded
			dataType: 'json', //para ser entendida como objeto no formato json
			success: dados => {
				//console.log(dados.numeroVendas, dados.totalVendas)
				$('#numeroVendas').html(dados.numeroVendas)
				$('#totalVendas').html(dados.totalVendas)				
				$('#clientesAtivos').html(dados.clientesAtivos)
				$('#clientesInativos').html(dados.clientesInativos)
				$('#totalDespesas').html(dados.totalDespesas)
				$('#totalReclamaoes').html(dados.totalRecamacoes)
				$('#totalElogios').html(dados.totalElogios)
				$('#totalSugestoes').html(dados.totalSugestoes)
				},
			error: erro => {console.log(erro)},
		})
	})
})