<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>

<div id="produit" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>
<div id="monDiv" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>

<pre id="tsv" style="display:none"></pre>

<script src="jquery.min.js" type="text/javascript"></script>
<script src="bootstrap.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
  //Notre premier graphique
  var prod = {
    chart: {
      renderTo: 'produit',
      type: 'column'
    },
    title: { //titre
        text: 'Ventes de produits par mois'
    },
    subtitle: {
        text: 'En valeurs'
    },
    tooltip: {    //options aux passages de la souris sur un mois
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} €</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    xAxis: {
        categories: [ // différents mois
            'Jan',
            'Feb',
            'Mar',
            'Avr',
            'Mai',
            'Jun',
            'Jui',
            'Aou',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Ventes ( en € )'
        }
    },
    series: []
  };


  var valeur = [0,0,0,0];

  var cumulateur;
  var categories =[];
$.getJSON('ventes.json', function(data) {

  $.each(data,function(index,objet){
    //console.log(objet);
  //  if(objet.)
  });


  $.each(data, function(i,produit){
    prod.series[i]={name:produit.Produit,data:produit.ventes}
    cumulateur=0;
    for(var j=0; j<11;j++){
      cumulateur+=produit.ventes[j];
    }
    //console.log(cumulateur);
    //console.log(produit.categorie.libelle);
    positionCateg= categories.findIndex(function(a){return a.id==produit.categorie.id});
    if(positionCateg<0){ //Catégorie inexistante dans le tableau
        categories.push({id:produit.categorie.id,name:produit.categorie.libelle,y:cumulateur});
    }
    else{ //Cumul
        categories[positionCateg].y += cumulateur;
    }
  });


    var $container = $('#monDiv');
    var monGraphe =  new PieGraph($container);
    monGraphe.setTitle("Ventes de produits par catégories");
    monGraphe.setSeries([{name:'Vente 2015',data:categories,}]);
    monGraphe.draw(); //Affichage




charts=new Highcharts.chart(prod);
});
var PieGraph = function(container) {

    //Option par défaut
    this.options = {
        credits: {
            enabled: false
        }, series: []
    };


    //Affichage des données


    /**
     * Paramétrage des options du graphe
     */
    this.setOptions = function(options){


        this.options = $.extend(this.options,{
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:.1f}  €</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                        enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            }
        });

    };

    /**
     * Ajout d'une série
     */
    this.setSeries =function(series){
        this.options.series = typeof series ==='undefined'?[]:series;
    };

    /**
     * Ajout de données
     */
    this.setData = function(data,idSerie){
        idSerie = typeof idSerie ==='undefined'?0:parseInt(idSerie);
        this.options.series[idSerie].data= data;
    };

    /**
     * Affichage du graphe
     * @param title
     * @param options
     */
    this.draw = function () {
        Highcharts.chart('monDiv',this.options);
    };

    /**
     * Titre
     * @param title
     */
    this.setTitle = function(title){
        this.options.title = {text:title};
    };


    /**
     * @param container
     */
    this.setContainer = function (container){
        this.container =  typeof  container ==='string'?$('#'+container):container ;
    };

    //Initialisation de l'objet
    this.setContainer(container);
    this.setOptions();

};
});//fin de fonction


</script>
