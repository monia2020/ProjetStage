// Vitesse carousel marque

$('.carou').carousel({
    interval: 1500
  })


var $produit_categorie = $("#produits_categorie")
var $token = $("#produits_token")

$produit_categorie.change(function()
{
  var $form = $(this).closest('form')
  var data = {}
  data[$token.attr('name')] = $token.val()
  data[$produit_categorie.attr('name')] = $produit_categorie.val()
  $.produit($form.attr('action'), data).then(function (response)
  {
    $("#produits_sousCategories").replaceWith(
      $(response).find("#produits_sousCategories")
    )
  })
})