rndefine("#RNMainSingleLineGenerator",["exports"],(function(t){"use strict";t.SingleLineGenerator=class{constructor(t,e={}){this.FormBuilder=t,this.Options=e,null==this.Options.ParseTagString&&(this.Options.ParseTagString=t=>null)}ParseContent(t){if(null==t)return"";let e="";for(let n of t.content){let t=null;if(null!=n.attrs&&(t=this.Options.ParseTagString(n.attrs)),null==t)switch(n.type){case"text":e+=n.text;break;case"field":if("Field"==n.attrs.Type){let t=this.FormBuilder.GetFieldById(n.attrs.Value);null!=t&&(null!=this.Options.FieldFormatter?e+=this.Options.FieldFormatter(t):e+=t.GetText())}else if(null!=this.Options.UnknownFormatter){let t=this.Options.UnknownFormatter(n);null!=t&&(e+=t)}}else e+=t}return e}static CreateText(t){return{type:"doc",content:[{type:"text",text:t}]}}},Object.defineProperty(t,"__esModule",{value:!0})}));