function DomManager() {
    this.rows = new Array();
    this.data = null;
    this.container = 'container';
}

DomManager.prototype.setData = function(data) {
    this.data = data;
};

DomManager.prototype.setRows = function(data) {
    this.rows = data;
};

DomManager.prototype.setContainer = function(container) {
    this.container = container;
};

DomManager.prototype.load = function() {
    this.refreshTable();
};

DomManager.prototype.createTable = function() {
    this.content = $('<table class="table table-bordered">\n\
                    <thead>\n\
                        <tr>\n\
                            <th class="col-md-2">Nombre</th>\n\
                            <th class="col-md-3">Visita</th>\n\
                            <th class="col-md-4">Tiempo</th>\n\
                            <th class="col-md-3">Ubicación</th>\n\
                        </tr>\n\
                    </thead>\n\
                    <tbody id="content"></tbody>\n\
                </table>');
    
    $('#' + this.container).append(this.content);
};

DomManager.prototype.refreshTable = function() {
    this.content.find('#content').hide();
    this.content.find('#content').empty(); 
    if (this.rows.length > 0) {
        for (var i = 0; i < this.rows.length; i++) {
            var visit = (this.rows[i].lastVisit == null || this.rows[i].lastVisit === 'null' ? 'No Disponible' : this.rows[i].lastVisit);
            var observation = (this.rows[i].observation == null || this.rows[i].observation === 'null' ? 'No Disponible' : this.rows[i].observation);
            var row = $('<tr>\n\
                            <td><strong>' + this.rows[i].name + '</strong></td>\n\
                            <td><span style="font-size: 1.4em; font-weight: 800;">' + this.rows[i].client + '</span><br>' + this.rows[i].visit + '<br><a data-toggle="collapse" href="#details-' + this.rows[i].idVisit + '" aria-expanded="false" aria-controls="details-' + this.rows[i].idVisit + '">Ver detalles</a></td>\n\
                            <td><strong>Entrada</strong>: ' + this.rows[i].start + '<br /><strong>Salida</strong>: ' + this.rows[i].end + '</td>\n\
                            <td>\n\
                                <strong><a href="' + url + '/map/' + this.rows[i].idVisit + '" target="_blank">' + this.rows[i].location + '</a></strong><br />\n\
                                <a href="' + url + '/maphistory/' + this.rows[i].idUser + '" target="_blank">Ver historial</a>\n\
                            </td>\n\
                        </tr>\n\
                        <tr class="collapse" id="details-' + this.rows[i].idVisit + '">\n\
                            <td colspan="4">\n\
                                <table class="table table-bordered" style="width: 70%;" align="center">\n\
                                    <thead></thead>\n\
                                    <tbody>\n\
                                        <tr>\n\
                                            <td><strong>Estado de la batería</strong></td>\n\
                                            <td>' + this.rows[i].battery + '%</td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td><strong>Tiempo transcurrido desde la última visita</strong></td>\n\
                                            <td>' + this.rows[i].lastVisit + '</td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td><strong>Observaciones</strong></td>\n\
                                            <td>' + this.rows[i].observation + '</td>\n\
                                        </tr>\n\
                                    </tbody>\n\
                                </table>\n\
                            </td>\n\
                        </tr>');


            this.content.find('#content').append(row);
        }
        
        this.content.find('#content').show('slow');
    }
    else {
        var row = $('<tr class="text-center"><td colspan="7">No hay registros para mostrar.</td></tr>');
        this.content.find('#content').append(row);
        this.content.find('#content').show('slow');
    }
    
    
};
