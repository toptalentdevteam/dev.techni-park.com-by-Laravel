@section('temp')
<style>
.date_document{
    float: left;
}
.title_document{
    float: left;
}
.table_header{
    width: 100%;
}
.table_header td{
    border: none;
    padding: 0.5em;
}
.table_sig{
    width: 100%;
}
.table_sig td{
    border: none;
    padding: 0.6em;
    font-size: 20px;
}
.table_raport{
    width: 100%;
    margin-top: 1em;
}
.table_raport th{
    border: 1px solid black;
    padding: 0.5em;
}
.table_raport td{
    border-top: 1px dotted black;
    border-bottom: 1px dotted black;
    border-left: none;
    border-right: none;
    padding: 0.5em;
}
.date_sig{
    font-size: 14px !important;
    margin-top: 1em;
}
.center{
    text-align: center;
}
.right{
    text-align: right;
}
.signature img{
    
    /*border: 2px solid black;*/
    /*min-height: 4em;*/
    width: 10em;
    height: auto;
    margin: 0px;
}
hr{
    color: cyan;
}
.color_red{
    /*color: red;*/
    font-weight: bold;
}


</style>

<div>
	<div class="header">
        <table class="table_header" rules="all">
            <tr>
                <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
                <td>Rapport mensuel | TECHNIPARK</td>
            </tr>
        </table>
        <hr>
        <table class="table_sig" rules="all">
            <tr>
                <td class="color_red">Nom collaborateur : {{ $nom ?? '' }}</td>
                <td>Signature :</td>
                <td rowspan="2">@if(isset($signature->CONTENUFIC))<div class="signature"><img src="data:image/jpeg;base64,{{ base64_encode($signature->CONTENUFIC) }}" /></div>@endif</td>
            </tr>
            <tr>
                <td class="color_red">Période : {{ $periode ?? '' }}</td>
                <td><span class="date_sig">le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span></td>
            </tr>
        </table>
		
	</div>
	<div class="content">

		<table class="table_raport" rules="all">
			<thead>	
			<tr>
				<th width="6%">{{ __('boilerplate::temps.list.intervention') }}</th>
				<th width="26%">{{ __('boilerplate::temps.list.projet') }}</th>
				<th width="8%">{{ __('boilerplate::temps.list.date') }}</th>
				<th width="6%">{{ __('boilerplate::temps.list.debut') }}</th>
				<th width="6%">{{ __('boilerplate::temps.list.fin') }}</th>
				<th width="6%">{{ __('boilerplate::temps.list.dure') }}</th>
				<th width="6%">{{ __('boilerplate::temps.list.pause') }}</th>
                <th width="6%">{{ __('boilerplate::temps.list.route') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.repasmidi') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.repassoir') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.grand') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.autonome') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.logistique') }}</th>
                <th width="5%">{{ __('boilerplate::temps.list.supplement') }}</th>
			</tr>  
            <?php
                $TOTAL_DUREE = strtotime('00:00');
                $TOTAL_PAUSE = strtotime('00:00');
                $TOTAL_ROUTE = strtotime('00:00');
                $total_min_duree = 0;
                $TOTAL_REPMIDI = 0;
                $TOTAL_REPSOIR = 0;
                $TOTAL_NUITEE = 0;
                $TOTAL_AUTONOME = 0;
                $TOTAL_LOGISTIQUE = 0;
                $TOTAL_SUPPLEMENT = 0;
            ?>
            @foreach($tiemposConso as $tiempo)  
                    <?php
                        $horas = substr($tiempo->DUREE_CONSO, 1, 2);
                        $minutos = substr($tiempo->DUREE_CONSO, 3, 2);
                        $dure = str_pad($horas, 2, "0", STR_PAD_LEFT).':'.str_pad($minutos, 2, "0", STR_PAD_LEFT);

                        $total_min_duree += (int)$horas*3600 + (int)$minutos*60;

                        $TOTAL_DUREE += strtotime($dure) + 3600;
                        $TOTAL_PAUSE += strtotime($tiempo->PAUSE) + 3600;
                        $TOTAL_ROUTE += strtotime($tiempo->ROUTE) + 3600;
                        $TOTAL_REPMIDI += $tiempo->REPMIDI;
                        $TOTAL_REPSOIR += $tiempo->REPSOIR;
                        $TOTAL_NUITEE += $tiempo->NUITEE;
                        $TOTAL_AUTONOME += $tiempo->AUTONOME;
                        $TOTAL_LOGISTIQUE += $tiempo->LOGISTIQUE;     
                        $TOTAL_SUPPLEMENT += $tiempo->SUPPLEMENT;

                       
                    ?>
                    <tr>
                        <td> 
                            {{ $tiempo->IDINTERVENTION }}
                        </td>
                        <td> 
                            {{ $tiempo->LIB50 }}
                        </td>
                        <td class="center">
                            {{ date('d/m/Y',strtotime($tiempo->DT_REAL)) }}
                        </td>
                        <td class="center">
                            {{ date('H:i',strtotime($tiempo->DHDEB)) }}
                        </td>
                        <td class="center">
                            {{ date('H:i',strtotime($tiempo->DHFIN)) }}
                        </td>
                        <td class="center">
                            {{ date('H:i',strtotime($dure)) }}
                        </td>
                        <td class="center">
                            {{ date('H:i',strtotime($tiempo->PAUSE)) }}
                        </td>
                        <td class="center">
                            {{ date('H:i',strtotime($tiempo->ROUTE)) }}
                        </td>
                        <td class="center">
                            {{ $tiempo->REPMIDI }}
                        </td>
                        <td class="center">
                            {{ $tiempo->REPSOIR }}
                        </td>
                        <td class="center">
                            {{ $tiempo->NUITEE }}
                        </td>
                        <td class="center">
                            {{ $tiempo->AUTONOME }}
                        </td>
                        <td class="center">
                            {{ $tiempo->LOGISTIQUE }}
                        </td>
                        <td class="center">
                            {{ $tiempo->SUPPLEMENT }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="center color_red" colspan="5"> 
                            TOTAUX : 
                    </td>
                    <td class="center color_red">
                        {{ conversorSegundosHoras($total_min_duree) }} 
                    </td>
                    <td class="center color_red">
                        {{ date('H:i',$TOTAL_PAUSE) }}
                    </td>
                    <td class="center color_red">
                        {{ date('H:i',$TOTAL_ROUTE) }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_REPMIDI }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_REPSOIR }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_NUITEE }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_AUTONOME }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_LOGISTIQUE }}
                    </td>
                    <td class="center color_red">
                        {{ $TOTAL_SUPPLEMENT }}
                    </td>
                </tr>
		</table>

	<div class="footer">
		<div>
				<p><span></span></p>
		</div>
	</div>
</div>
<?php
    function conversorSegundosHoras($tiempo_en_segundos) 
    {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
                        
        return str_pad($horas, 2, "0", STR_PAD_LEFT).':'.str_pad($minutos, 2, "0", STR_PAD_LEFT);
    }
?>
@show