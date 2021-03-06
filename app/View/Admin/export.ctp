<?php
if(isset($this->request->query["month"])){
    //input the export file name

        if(isset($this->request->query["type"])){


        } else {
            if(isset($this->request->query["webview"])){
                echo $this->Admin->webview($data);
            } else {

                //Parse XML headers
                $this->xls->setHeader('Schaubroeck_export_'.date('Y_m_d'));
                $this->xls->addXmlHeader();
                $this->xls->setWorkSheetName('Data');

                //1st row for columns name
                $this->xls->openRow();
                $this->xls->writeString('Naam');
                $this->xls->writeString('Voornaam');

                foreach($dateRange as $date){
                    if(explode('/', $date)[1] == "AM"){
                        $exportDate = explode('/', $date)[0] . ' 6:00:00';
                    } else {
                        $exportDate = explode('/', $date)[0] . ' 12:00:00';
                    }
                    $this->xls->writeString($exportDate);
                }

                $this->xls->closeRow();

                foreach($data as $employee => $days){

                    $this->xls->openRow();
                    $this->xls->writeString(explode(' ', $employee)[1]);
                    $this->xls->writeString(explode(' ', $employee)[0]);

                    foreach($days as $date => $type){
                        if(count($type) == 2){
                            $type = $type[1];
                        }

                        $this->xls->writeString($type);

                    }

                    $this->xls->closeRow();
                }
                $this->xls->addXmlFooter();
                exit();
            }

        }


} else {?>

    <div class="row">
        <div class="col-md-3">
            <?php echo $this->element('admin/base_admin_menu');?>
        </div>
        <div class="col-md-9">
            <h2 class="first">Een export opstellen</h2>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-primary fullwidth spaced" href="<?php echo $this->here;?>?month=<?php echo date('m');?>">Genereer standaardexport</a>
                    <a class="btn btn-success fullwidth spaced" href="<?php echo $this->here;?>?month=<?php echo date('m');?>&webview=1">Bekijk dit in de webview</a>
                </div>
                <div class="col-md-6">
                    <h4 class="first">Standaardexport</h4>
                    <p>Deze export is de standaardexport, waar iedereen wordt opgelijst van de eerste van de maand tot de laatste van de maand.</p>
                </div>
            </div><hr />
            <div class="row">
                <div class="col-md-6">
                    <input type="number" class="form-control spaced" placeholder="Dag van de maand" id="daynumber-2">
                    <a class="btn btn-primary fullwidth spaced" onClick="exportDay(2)">Genereer gelimiteerde export</a>
                    <a class="btn btn-success fullwidth spaced" onClick="exportDay(2, 'webview')">Bekijk dit in de webview</a>
                </div>
                <div class="col-md-6">
                    <h4 class="first">Gelimiteerde export</h4>
                    <p>Deze export dient om wijzigingen door te geven die na een bepaalde dag vallen.</p>
                </div>
            </div><hr />
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-primary fullwidth" href="<?php echo $this->here;?>?month=<?php echo date('m');?>&type=1">Genereer Schaubroeck export</a></div>
                <div class="col-md-6">
                    <h4 class="first">Schaubroeck export</h4>
                    <p>Deze export doet net hetzelfde als de standaardexport, maar dan voor het toekomstig Schaubroeck-systeem.</p>
                </div>
            </div><hr />
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 formspaced-left">
                            <input type="number" class="form-control spaced" placeholder="Dag van de maand" id="daynumber-4">
                        </div>
                        <div class="col-md-6 formspaced-right">
                            <select class="form-control spaced" id="month-4">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maart</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Augustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                    </div>
                    <a class="btn btn-primary fullwidth" onClick="exportMonth(4)">Genereer specifieke export</a></div>
                <div class="col-md-6">
                    <h4 class="first">Andere maand</h4>
                    <p>Exporteer een voorgaande maand, al dan niet met een afwijkende startdatum.</p>
                </div>
            </div><hr />
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-danger fullwidth" href="<?php echo $this->base;?>/Admin/ignoreExports">Exports negeren</a>
                </div>
                <div class="col-md-6">
                    <h4 class="first">Exports negeren</h4>
                    <p>Als je een export wilt ongedaan maken in het systeem, dan moet je deze negeren. De bestanden worden dan niet verwijderd, maar het systeem houdt geen rekening met de voorgaande.</p>
                </div>
            </div><hr />
        </div>
    </div>

    <script>
        function exportDay(rownr, webview){
            var webview = webview || null;
            var daynr = $('#daynumber-' + rownr).val();
            if(daynr != null){
                if(webview != null){
                    window.location.href = "<?php echo $this->here;?>?month=<?php echo date('m');?>&limit=" + daynr + "&webview=1";
                } else {
                    window.location.href = "<?php echo $this->here;?>?month=<?php echo date('m');?>&limit=" + daynr;
                }
            }
        }

        function exportMonth(rownr){
            var monthnr = $('#month-' + rownr).val();
            var daynr = $('#daynumber-' + rownr).val();
            if(daynr == ''){
                daynr = 1;
            }
            if(monthnr !== null){
                window.location.href = "<?php echo $this->here;?>?month=" + monthnr + "&limit=" + daynr;
            }
        }

        function exportMonthSchaubroeck(rownr){
            var monthnr = $('#month-' + rownr).val();
            var daynr = $('#daynumber-' + rownr).val();
            if(daynr == ''){
                daynr = 1;
            }
            if(monthnr !== null){
                window.location.href = "<?php echo $this->here;?>?month=" + monthnr + "&limit=" + daynr + '&type=1';
            }
        }
    </script>
<?php }?>

