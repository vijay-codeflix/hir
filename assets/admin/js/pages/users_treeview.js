
var data = getData();

// Ajax function to get data
function getData(){
    // console.log('ajax iniialize');
    $.ajax({
        url:base_url + "admin/users/getUsersTreeView",
        method: "post",
        dataType: 'JSON',
        contentType: "application/json; charset=utf-8",
        success: function(result){
            Object.keys(result).map( key => {
                result[key].tags = [result[key].title];
            });
            // console.log(result);
            // var treeData = result
            initializeTree(result);
            
        }
    });
 }   
 
    
//Tree JavaScript
function initializeTree(result){
        
        // console.log(result);
        // nodes = result;
     
        OrgChart.templates.polina.link = '<path stroke-linejoin="round" stroke="#aeaeae" stroke-width="1px" fill="none" d="{rounded}" />';
        OrgChart.templates.ana.link = '<path stroke-linejoin="round" stroke="#aeaeae" stroke-width="1px" fill="none" d="{rounded}" />';
        
        OrgChart.templates.ana = Object.assign({}, OrgChart.templates.ana);
        OrgChart.templates.ana.node = '<rect x="0" y="0" height="120" width="250" fill="#0066b2" stroke-width="1" stroke="#aeaeae" rx="7" ry="7"></rect>';
        OrgChart.templates.ana.field_0 = '<text class="field_0"  style="font-size: 20px;" fill="#ffffff" x="125" y="30" text-anchor="middle">{val}</text>';
        OrgChart.templates.ana.field_1 = '<text class="field_1"  style="font-size: 14px;" fill="#ffffff" x="125" y="50" text-anchor="middle">{val}</text>';
        OrgChart.templates.ana.field_2 = '<text class="field_2"  style="font-size: 14px;" fill="#ffffff" x="125" y="70" text-anchor="middle">{val}</text>';
        OrgChart.templates.ana.field_3 = '<text class="field_3"  style="font-size: 14px;" fill="#ffffff" x="125" y="90" text-anchor="middle">{val}</text>';

        OrgChart.templates.ana.min = Object.assign({}, OrgChart.templates.ana);
        OrgChart.templates.ana.min.size = [100, 50];
        OrgChart.templates.ana.min.node = '<rect x="0" y="0" height="{h}" width="{w}" fill="#0066b2" stroke-width="1" stroke="#aeaeae" rx="7" ry="7"></rect>';
        OrgChart.templates.ana.min.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle>'
        + '<text text-anchor="middle" style="font-size: 18px;cursor:pointer;" fill="#757575" x="15" y="22">{collapsed-children-total-count}</text>';
        OrgChart.templates.ana.min.field_0 = '';
        OrgChart.templates.ana.min.field_1 = '<text data-width="80" style="font-size: 12px;" fill="#ffffff" x="50" y="30" text-anchor="middle">{val}</text>';
        OrgChart.templates.ana.min.field_2 = '';
        OrgChart.templates.ana.min.field_3 = '';
        
        OrgChart.templates.polina = Object.assign({}, OrgChart.templates.polina);
        OrgChart.templates.polina.node = '<rect x="0" y="0" height="120" width="250" fill="#2FA4FF" stroke-width="1" stroke="#aeaeae" rx="60" ry="60"></rect>';
        OrgChart.templates.polina.field_0 = '<text class="field_0"  style="font-size: 20px;" fill="#ffffff" x="125" y="30" text-anchor="middle">{val}</text>';
        OrgChart.templates.polina.field_1 = '<text class="field_1"  style="font-size: 14px;" fill="#ffffff" x="125" y="50" text-anchor="middle">{val}</text>';
        OrgChart.templates.polina.field_2 = '<text class="field_2"  style="font-size: 14px;" fill="#ffffff" x="125" y="70" text-anchor="middle">{val}</text>';
        OrgChart.templates.polina.field_3 = '<text class="field_3"  style="font-size: 14px;" fill="#ffffff" x="125" y="90" text-anchor="middle">{val}</text>';

        OrgChart.templates.polina.min = Object.assign({}, OrgChart.templates.polina);
        OrgChart.templates.polina.min.size = [100, 50];
        OrgChart.templates.polina.min.node ='<rect x="0" y="0" height="{h}" width="{w}" fill="#2FA4FF" stroke-width="1" stroke="#aeaeae" rx="25" ry="25"></rect>';
        OrgChart.templates.polina.min.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#aeaeae" stroke-width="1"></circle>'
        + '<text text-anchor="middle" style="font-size: 18px;cursor:pointer;" fill="#757575" x="15" y="22">{collapsed-children-total-count}</text>';
        OrgChart.templates.polina.min.field_0 = '';
        OrgChart.templates.polina.min.field_1 = '<text data-width="80" style="font-size: 12px;" fill="#ffffff" x="50" y="30" text-anchor="middle">{val}</text>';
        OrgChart.templates.polina.min.field_2 = '';
        OrgChart.templates.polina.min.field_3 = '';
        
         
        var chart = new OrgChart(document.getElementById("tree"), {
            template: "rony",
            mouseScrool: OrgChart.action.none,
            keyNavigation: {
                focusId: 1
            },
            collapse: {
                level: 2,
                allChildren: true,
            },
            tags: {
                "Super Admin": {
                    template: "ana"
                },
                "Admin": {
                    template: "ana"
                },
                "Sub Admin": {
                    template: "ana"
                },
                "Employee": {
                    template: "polina"
                },
                "Example": {
                    template: "polina"
                },
            },
            nodeBinding: {
                field_0: "title",
                field_1: "name",
                field_2: "email",
                field_3: "phone",
                // img_0: "img"
            },
             min: true,
            // nodes: treeData,
            });
            
            nodes = result;

            chart.on('click', function (sender, args) {
                if (args.node.min) {
                    sender.maximize(args.node.id);
                }
                else {
                    sender.minimize(args.node.id);
                }
                return false;
            });
            
            chart.on('key-down', function (sender, args) {
                if (args.node) {
                    if (args.event.code == "Enter" || args.event.code == "NumpadEnter") {
                        if (args.node.min) {
                            sender.maximize(args.node.id);
                        }
                        else {
                            sender.minimize(args.node.id);
                        }
                        return false;
                    }
                }
            });
            
            chart.load(nodes);
            
}