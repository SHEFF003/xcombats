function getalign(al){  var n=parseFloat(al);  if (n>=1 && n<2) return("����� ��������");  if (n>=2 && n<3) return("�����");  if (n>=3 && n<4) return("������ ��������");  if (n==7) return("����������� ��������");  if (n>7 && n<8) return("����� �������� ������");  if (n>=50 && n<51) return("��������");  return("");}
function getalignurl(al){  var n=parseFloat(al);  if (n==50) return "http://xcombats.com/encicl/alchemist.html";  return "http://xcombats.com/encicl/alignment.html";}
function drwfl(name, id, level, align, klan, img, sex){  var s="";  if (align!="0") s+="<A HREF='"+getalignurl(align)+"' target=_blank><IMG SRC='http://img.xcombats.com/i/align/align"+align+".gif' WIDTH=12 HEIGHT=15 ALT=\""+getalign(align)+"\"></A>";  if (klan) s+="<A HREF='/clans_info/"+klan+"' target=_blank><IMG SRC='http://img.xcombats.com/i/clan/"+klan+".gif' WIDTH=24 HEIGHT=15 ALT=''></A>";  s+="<B>"+name+"</B>";  if (level!=-1) s+=" ["+level+"]";  if (id!=-1 && !img) s+="<A HREF='http://xcombats.com/info/"+id+"' target='_blank'><IMG SRC=http://img.xcombats.com/i/inf_capitalcity.gif WIDTH=12 HEIGHT=11 ALT='���. � "+name+"'></A>";  if (img) s+="<A HREF='http://capitalcity.xcombats.com/encicl/obraz_"+(sex?"w":"m")+"1.html?l="+img+"' target='_blank'><IMG SRC=http://capitalcity.img.xcombats.com/i/inf.gif WIDTH=12 HEIGHT=11 ALT='����� "+name+"'></A>";  document.write(s);} 