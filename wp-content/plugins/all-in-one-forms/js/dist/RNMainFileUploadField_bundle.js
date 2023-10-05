rndefine("#RNMainFileUploadField",["#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FieldWithPrice.Model","lit","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/FieldWithPrice","lit/directives/repeat.js","#RNMainLit/Lit","lit/directives/ref.js","#RNMainCoreUI/ToastService","#RNMainFormBuilderCore/FieldWithPrice.Options"],(function(e,i,t,l,s,r,a,n,d,o,h,p){"use strict";class F extends t.FieldWithPriceModel{constructor(e,i){super(e,i),this.Options=e,this.SavedFileNames=[]}GetValue(){return this.Files.filter((e=>e.File.length>0||e.FileId>0)).map((e=>({Id:e.Id,Name:e.Name,total:e.total,FileId:e.FileId})))}GetStoresInformation(){return!0}CreateRow(e="",i=0){return new u(e,i)}InternalSerialize(e){super.InternalSerialize(e),e.Value=this.GetValue()}GetIsUsed(){return!!super.GetIsUsed()&&this.GetValue().length>0}ListValueChanged(e,i,t){this.Refresh()}CreateRowAt(e){let i=this.Files.slice(0);i.splice(i.indexOf(e)+1,0,this.CreateRow()),this.Files=i,this.FireValueChanged()}GetDynamicFieldNames(){return["FBFile"]}InitializeStartingValues(){let e=this.GetPreviousDataProperty("Value",[]);if(this.Files=[],e.length>0)for(let i of e)this.Files.push(this.CreateRow(i.Name,i.FileId));this.IsReadonly||this.Files.push(this.CreateRow())}GetFiles(){let e=[];for(let i of this.Files)null!=i.File[0]&&e.push({Id:i.Id,File:i.File[0]});return e}render(){return l.html`<rn-file-upload-field .model="${this}"></rn-file-upload-field>`}}class u{constructor(e,i){this.Name=e,this.File=[],this.FileId=i,this.Highlight=!1,this.Id=++u._lastId,this.total={Price:0,Quantity:0,RegularPrice:0}}GetName(){return this.FileId>0?this.Name:this.File.length>0?this.File[0].name:void 0}}u._lastId=0;var m,c={};!function(e){Object.defineProperty(e,"__esModule",{value:!0});var i="minus-circle",t=[],l="f056",s="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zM124 296c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h264c6.6 0 12 5.4 12 12v56c0 6.6-5.4 12-12 12H124z";e.definition={prefix:"fas",iconName:i,icon:[512,512,t,l,s]},e.faMinusCircle=e.definition,e.prefix="fas",e.iconName=i,e.width=512,e.height=512,e.ligatures=t,e.unicode=l,e.svgPathData=s}(c);let f=s.customElement("rn-file-upload-field")(m=class extends a.FieldWithPrice{static get properties(){return r.FieldBase.properties}SubRender(){return l.html` <div style="width: 100%"> <table class='rnFileContainer' style="width: 100%;border-collapse: collapse;border: none;margin:0;"> <tbody> ${this.GenerateRows()} </tbody> </table> </div> `}GenerateRows(){return n.repeat(this.model.Files,(e=>e.FileId),(e=>l.html` <tr class='rednao'> <td style="border: none;padding: 6px 0"> <div style="width: 100%"> ${0!=e.File.length||e.FileId>0?l.html` <div style="padding: 5px;border: 1px solid #ccc;flex-grow: 1"> <span>${e.GetName()}</span> </div> `:l.html` <div style="padding: 5px;border: 1px dashed;cursor: pointer;flex-grow: 1;" class=${"rnFilePlaceholder "+(e.Highlight?"active":"")} @click=${i=>{e.Ref.click()}} @dragleave=${i=>this.FOnDragExit(e)} @dragenter=${i=>this.FOnDragEnter(i,e)} @dragover=${i=>this.FOnDragOver(i,e)} @drop=${i=>this.FOnDrop(e,i)}> <span>${this.IsDragAndDropSupported()?RNTranslate("Drag or click here to add a file"):RNTranslate("Click here to add a file")}</span> </div>`} </div> <input ${o.ref((i=>e.Ref=i))} accept=${this.model.Options.AllowedExtensions} type='file' name=${"rnProFile"+this.model.Options.Id+"@"+e.Id} style="display: none" @change=${i=>this.FileChanged(e,i.target.files)}/> </td> ${d.rnIf(!this.model.IsReadonly&&l.html` <td style="border: none;width: 25px;"> <div style="margin-left: 5px;display: flex"> ${d.rnIf((e.File.length>0||e.FileId>0)&&l.html` <rn-fontawesome @click=${()=>this.RemoveFile(e)} class='RNFBFileRemove' style="font-size: 18px;" .icon=${c.faMinusCircle}></rn-fontawesome> `)} </div> </td> `)} </tr> `))}FileChanged(e,i){if(e.File=i,0==i.length&&this.model.Files.length>1)this.RemoveFile(e);else{let i=e.File[0].name;if(!this.ExtensionIsValid(i))return e.File=[],void(e.Ref.value="");e.Name=e.File[0].name}this.model.Options.AllowMultipleFiles&&0==this.model.Files.filter((e=>0==e.File.length)).length&&this.model.Files.push(this.model.CreateRow()),this.model.FireValueChanged()}FOnDrop(e,i){if(!this.IsDragAndDropSupported())return;i.preventDefault(),e.Highlight=!1;let t=i.dataTransfer.files;0!=t.length?t.length>0&&!this.ExtensionIsValid(t[0].name)?t.length>0&&(e.File=[],e.Ref.value=""):(e.FileId=0,e.File=i.dataTransfer.files,e.Name=i.dataTransfer.files[0].name,e.Ref.files=e.File,this.model.Options.AllowMultipleFiles&&0==this.model.Files.filter((e=>0==e.File.length)).length&&this.model.Files.push(this.model.CreateRow()),this.forceUpdate()):this.RemoveFile(e)}ExtensionIsValid(e){e=e.toLowerCase().trim();let i=this.model.Options.AllowedExtensions.trim().split(",");return!(i.length>0&&!i.some((i=>e.endsWith(i.toLowerCase().trim()))))||(h.ToastService.SendErrorMessage("Invalid file type"),this.forceUpdate(),!1)}RemoveFile(e){e.FileId=0,e.Ref.value="",this.model.Files.indexOf(e)>=0&&(this.model.Files.splice(this.model.Files.indexOf(e),1),0==this.model.Files.length&&this.model.Files.push(this.model.CreateRow())),this.model.FireValueChanged()}FOnDragOver(e,i){this.IsDragAndDropSupported()&&(e.preventDefault(),0==i.Highlight&&(i.Highlight=!0,this.forceUpdate()))}FOnDragEnter(e,i){this.IsDragAndDropSupported()&&(e.preventDefault(),i.Highlight=!0,this.forceUpdate())}FOnDragExit(e){this.IsDragAndDropSupported()&&(e.Highlight=!1,this.forceUpdate())}IsDragAndDropSupported(){let e=document.createElement("div");return("draggable"in e||"ondragstart"in e&&"ondrop"in e)&&"FormData"in window&&"FileReader"in window}})||m;class g extends p.FieldWithPriceOptions{LoadDefaultValues(){super.LoadDefaultValues(),this.Value="",this.Type=i.FieldTypeEnum.FileUpload,this.AllowMultipleFiles=!1,this.AllowedExtensions="",this.ButtonLabel="Add File",this.Label="File"}}exports.FileUploadFieldModel=F,exports.FileUploadField=f,exports.FileUploadFieldOptions=g,e.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==i.FieldTypeEnum.FileUpload)return new g})),e.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==i.FieldTypeEnum.FileUpload)return new F(e.Options,e.Parent)}))}));
