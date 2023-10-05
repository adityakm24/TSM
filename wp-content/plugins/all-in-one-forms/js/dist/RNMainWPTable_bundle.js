rndefine("#RNMainWPTable",["#RNMainCore/LitElementBase","lit/decorators","lit","#RNMainLit/Lit","#RNMainCore/SingleEvent","lit-html/directives/live.js","#RNMainCore/Sanitizer"],(function(t,e,i,s,a,l,n){"use strict";var r,h={};!function(t){Object.defineProperty(t,"__esModule",{value:!0});var e="spinner",i=[],s="f110",a="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z";t.definition={prefix:"fas",iconName:e,icon:[512,512,i,s,a]},t.faSpinner=t.definition,t.prefix="fas",t.iconName=e,t.width=512,t.height=512,t.ligatures=i,t.unicode=s,t.svgPathData=a}(h);let o=e.customElement("rn-wptable")(r=class extends t.LitElementBase{static get properties(){return{emptyText:{type:Object},availableSizes:{type:Object},pageSize:{type:Object},pageIndex:{type:Object},tableIsBusy:{type:Object},tableIsBusyMessage:{type:Object},Data:{type:Object}}}constructor(){super(),this.emptyText=null,this.isClickable=!1,this.availableSizes=[],this.Data=[],this.fillAvailableWidth=!1,this.totalNumberOfRows=0,this.bulkActions=[],this.SelectedRows=[],this.bulkActionIsBusy=!1,this.OnRefresh=new a.SingleEvent,this.Columns=[]}get TotalNumberOfPages(){return this.HasRows?Math.ceil(this.totalNumberOfRows/this.pageSize):0}GetParentStyles(){return{display:"block"}}render(){let t={};return this.fillAvailableWidth&&(t.width="100%",t.overflow="auto"),this.OnRefresh.Publish(),i.html` <div style="position: relative" > ${s.rnIf(this.tableIsBusy&&i.html` <div style="background-color: rgba(0,0,0,.5);position:absolute; top:0;left: 0;width: 100%;height: 100%;display: flex;align-items: center;justify-content: center;"> <rn-fontawesome style="font-size: 30px;color: white;margin-right: 5px;" .spin="${!0}" .icon="${h.faSpinner}"></rn-fontawesome> <span style="font-size: 30px;color: white;">${this.tableIsBusyMessage}</span> </div> `)} ${this.MaybeGetNavigation()} <div style="${i.rnsg(t)}"> <table class="rnTable wp-list-table widefat fixed striped entries" style="border: none"> <thead> <tr> ${this.Columns.map((t=>i.html`<th style="${i.rnsg({width:this.GetColumnWidth(t.Width)})}"> <rn-wpth .Table="${this}" .Column="${t}"></rn-wpth> </th>`))} </tr> </thead> <tbody> ${0==this.Data.length&&null!=this.emptyText?i.html` <tr> <td colspan="${this.Columns.length}">${this.emptyText}</td> </tr> `:this.Data.map((t=>i.html` <tr> ${this.Columns.map((e=>i.html`<td style="${i.rnsg({width:this.GetColumnWidth(e.Width)})}"><rn-wptd .Table="${this}" .Column="${e}" .Data="${t}"></rn-wptd></td>`))} </tr>`))} </tbody> </table> </div> ${this.MaybeGetNavigation()} </div> `}GoToPage(t){isNaN(t)&&(t=this.pageIndex),(t=Number(t))<0&&(t=0),t>=Number(this.TotalNumberOfPages)&&(t=this.TotalNumberOfPages-1),this.pageIndex!=t?this.FireEvent("pageChanged",t,!0):this.forceUpdate()}get HasNextPage(){return this.pageIndex+1<this.TotalNumberOfPages}get HasPrevPage(){return this.pageIndex>0}get HasRows(){return this.totalNumberOfRows>0}MaybeGetNavigation(){return i.html`<rn-wp-table-navigation .table="${this}"></rn-wp-table-navigation>`}GetColumnWidth(t){return null==t||t.toString().endsWith("px")||t.toString().endsWith("%")?t:t+"%"}updated(t){super.updated(t),t.has("Data")&&(this.SelectedRows=[])}ToggleSelection(t,e){if(e)this.SelectedRows.some((e=>e==t))||this.SelectedRows.push(t);else{let e=this.SelectedRows.indexOf(t);e>=0&&this.SelectedRows.splice(e,1)}}ToggleAll(t){this.SelectedRows=t?this.Data:[],this.forceUpdate()}})||r;var c;let d=e.customElement("rn-wpth")(c=class extends t.LitElementBase{render(){return i.html` <div style="${i.rnsg({textAlign:this.GetAlignment()})}" class="${"sortDirectionClass rnStickyHeader column-primary "+(this.Column.IsNumeric?" rnNumeric":"")}"> ${s.rnIf(this.Column.HasCheckbox&&i.html`<input style="margin: 0" type='checkbox' @change="${t=>this.Table.ToggleAll(t.target.checked)}" ?checked="${this.Table.SelectedRows.length==this.Table.Data.length}"/>`)} ${this.Column.Header} </div> `}GetAlignment(){return this.Column.IsNumeric?"right":this.Column.HasCheckbox?"left":null}SortingChanged(){}})||c;var p;let u=e.customElement("rn-wptd")(p=class extends t.LitElementBase{static get properties(){return{Data:{type:Object}}}render(){return i.html` <div style="${i.rnsg({textAlign:this.GetAlignment()})}"> ${s.rnIf(this.Column.HasCheckbox&&i.html` <input type="checkbox" ?checked="${l.live(this.Table.SelectedRows.some((t=>t==this.Data)))}" @change="${t=>{this.Table.ToggleSelection(this.Data,t.target.checked),this.forceUpdate()}}"/> `)} ${this.GetValue()} ${this.Column.Actions.length>0?i.html` <div class="row-actions"> ${this.Column.Actions.map(((t,e)=>this.GenerateAction(t,e)))} </div> `:null} </div> `}GetAlignment(){return this.Column.IsNumeric?"right":this.Column.HasCheckbox?"left":null}SortingChanged(){}GetValue(){let t=this.Data[this.Column.PropertyName];return null!=this.Column.Formatter?this.Column.Formatter(t,this.Data):t}GenerateAction(t,e){return t.IsLink&&null!=t.OnGetLink?i.html`<span> ${e>0?"|":""} <a class="wptable-action" data-action-id="${t.Id}" href="${t.OnGetLink(this.Data)}">${t.Title}</a></span>`:i.html`<span> ${e>0?"|":""} <a class="wptable-action" data-action-id="${t.Id}" href=${"#"} @click="${e=>{e.preventDefault(),this.FireEvent("actionClicked",{Row:this.Data,Action:t.Id},!0)}}">${t.Title}</a> </span>`}})||p;var b;let g=e.customElement("rn-wp-table-navigation")(b=class extends t.LitElementBase{constructor(...t){super(...t),this.SelectedBulkAction=null}static get properties(){return{}}connectedCallback(){super.connectedCallback(),this.table.OnRefresh.Subscribe(this,(()=>this.forceUpdate()))}disconnectedCallback(){super.disconnectedCallback(),this.table.OnRefresh.Unsubscribe(this)}render(){return this.ShouldBeDisplayed()?i.html`<div style="display: flex;"> <div style="display: flex;align-items: center;"> <select ?disabled="${!this.table.HasRows}" @change="${t=>{this.SelectedBulkAction=t.target.value,this.forceUpdate()}}"> <option>Bulk Actions</option> ${this.table.bulkActions.map((t=>i.html` <option value=${t.Title}>${t.Title}</option> `))} </select> <rn-spinner-button .spinnerColor="${"#333333"}" .isBusy="${this.table.bulkActionIsBusy}" style="padding: 0;margin-left: 5px;border: none" @click="${t=>this.BulkActionClicked()}" ?disabled="${!this.table.HasRows||null==this.SelectedBulkAction}" class="rnbtn rnbtn-light" style="margin-left: 5px;" .label=${"Apply"}></rn-spinner-button> </div> <div style="display: flex;align-items: center;margin: 5px 0;margin-left:auto"> <span style="margin-right: 5px">Display</span> <select ?disabled="${!this.table.HasRows}" value="${l.live(this.table.pageSize)}" @change="${t=>this.FireEvent("pageSizeChanged",t.target.value,!0)}"> ${this.table.availableSizes.map((t=>i.html` <option ?selected="${t.Size==this.table.pageSize}" value=${t.Size}>${t.Label}</option> `))} </select> <span style="margin-left: 5px;margin-right: 30px;">Pages</span> ${this.table.totalNumberOfRows} <span style="margin-left: 5px;margin-right: 5px;">items</span> <button ?disabled="${!this.table.HasPrevPage}" @click="${t=>this.table.GoToPage(0)}" class="rnwpnavbutton rnbtn rnbtn-light" style="border: 1px solid #aaa;padding: 5px;width: 33px;margin-right: 5px;">${"<<"}</button> <button ?disabled="${!this.table.HasPrevPage}" @click="${t=>this.table.GoToPage(this.table.pageIndex-1)}" class="rnwpnavbutton rnbtn rnbtn-light" style="border: 1px solid #aaa;padding: 5px;width: 33px;margin-right: 5px;">${"<"}</button> <input ?disabled="${!this.table.HasRows}" @change="${t=>this.table.GoToPage(n.Sanitizer.SanitizeNumber(t.target.value)-1)}" .value="${l.live(this.table.HasRows?parseFloat(this.table.pageIndex.toString())+1:0)}" type="text" style="text-align: center;display: inline-block;width: 60px;margin-right: 5px;"/> <span style="margin-right: 5px;">of ${this.table.TotalNumberOfPages}</span> <button ?disabled="${!this.table.HasNextPage}" @click="${t=>this.table.GoToPage(this.table.pageIndex+1)}" class="rnwpnavbutton rnbtn rnbtn-light" style="border: 1px solid #aaa;padding: 5px;width: 33px;margin-right: 5px;">${">"}</button> <button ?disabled="${!this.table.HasNextPage}" @click="${t=>this.table.GoToPage(this.table.TotalNumberOfPages-1)}" class="rnwpnavbutton rnbtn rnbtn-light" style="border: 1px solid #aaa;padding: 5px;width: 33px;">${">>"}</button> </div> </div> `:null}ShouldBeDisplayed(){return this.table.availableSizes.length>0}BulkActionClicked(){let t=this.table.bulkActions.find((t=>t.Title==this.SelectedBulkAction));null!=t&&this.FireEvent("applyBulkAction",t.Id,!0)}})||b;exports.WPTable=o,exports.WPTableColumn=class{constructor(t,e,i=null){this.Header=t,this.PropertyName=e,this.Width=i,this.Formatter=null,this.IsNumeric=!1,this.StickyOffset=0,this.Actions=[],this.IsClickable=!1,this.HasCheckbox=!1}SetIsClickable(t=!0){return this.IsClickable=t,this}SetHasCheckbox(t=!0){return this.HasCheckbox=t,this}SetStickyOffset(t){return this.StickyOffset=t,this}SetIsNumeric(t=!0){return this.IsNumeric=t,this}AddFormatter(t){return this.Formatter=t,this}SetActions(t){return this.Actions=t,this}},exports.WPTH=d,exports.WPTD=u,exports.WPTableNavigation=g}));
