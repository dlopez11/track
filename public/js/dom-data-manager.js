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
                            <th class="col-md-2">Fecha</th>\n\
                            <th class="col-md-2">Nombre</th>\n\
                            <th class="col-md-2">Tipo de visita</th>\n\
                            <th class="col-md-2">Cliente</th>\n\
                            <th class="col-md-1">Entrada/Salida</th>\n\
                            <th class="col-md-1">Estado de batería</th>\n\
                            <th class="col-md-2">Observaciones</th>\n\
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
            var row = $('<tr>\n\
                            <td><strong>' + this.rows[i].name + '</strong></td>\n\
                            <td>' + this.rows[i].visit + '</td>\n\
                            <td>' + this.rows[i].client + '</td>\n\\n\
                            <td>Entrada: ' + this.rows[i].iin + '<br />Salida:' + this.rows[i].out + '</td>\n\
                            <td>' + this.rows[i].battery + '%</td>\n\
                            <td>' + this.rows[i].observation + '</td>\n\
                            <td>\n\
                                <strong><a href="' + url + '/map/' + this.rows[i].idVisit + '" target="_blank">' + this.rows[i].location + '</a></strong><br />\n\
                                <a href="' + url + '/maphistory/' + this.rows[i].idUser + '" target="_blank">Ver historial</a>\n\
                            </td>\n\
                        </tr>');


            this.content.find('#content').append(row);
        }
        
        this.content.find('#content').show('slow');
    }
    else {
        var row = $('<tr class="text-center"><td colspan="6">Sin registros</td></tr>');
        this.content.find('#content').append(row);
        this.content.find('#content').show('slow');
    }
    
    
};
