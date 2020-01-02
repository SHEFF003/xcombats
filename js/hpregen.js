var timerHP = 0;
var lasttimeHP = 0;
function startHpRegen(f,id,hpnow,hpmax,mpnow,mpmax,sec_h,sec_m,speed_hp,speed_mp,news,tmr)
{	
	var d = document;
	
	if(news!=0)
	{
		lasttimeHP++; tmr = lasttimeHP; timerHP = tmr;
	}
	
	
	if(news==1 && tt1!=undefined)
	{
		clearTimeout(tt1);
	}
	
	if(lasttimeHP!=tmr)
	{
		
	}else{	
		if(f=='top')
		{
			d = top.document;
		}else if(f=='main')
		{
			d = top.frames['main'].document;
		}
		
		if(d!=undefined && lafstReg[id]!=undefined && lafstReg[id]!=0)
		{
			//здоровье
			var h1 = d.getElementById('vhp'+id);
			var widb1 = h1 ? parseInt(h1.style.width) : 0;
			if(h1!=undefined)
			{
				var h2 = d.getElementById('lhp'+id);	
				if(h2!=undefined)
				{
					//Восстанавливаем НР
					var newHP = '';
						newHP = sec_h*speed_hp;
						if(newHP!=0)
						{
							hpnow += newHP;
							if(hpnow<0)
							{
								hpnow = 0;	
							}
							if(hpnow>hpmax)
							{
								hpnow = hpmax;	
							}
							h1.innerHTML = ' '+Math.floor(hpnow)+'/'+hpmax+'';
							if(Math.floor(hpnow/hpmax*widb1)<1)
							{
								h2.className = 'hp_none';	
							}
							if(Math.floor(hpnow/hpmax*widb1)>0)
							{
								h2.className = 'hp_1';
							}
							if(Math.floor(hpnow/hpmax*widb1)>32)
							{
								h2.className = 'hp_2';
							}
							if(Math.floor(hpnow/hpmax*widb1)>65)
							{
								h2.className = 'hp_3';
							}
							if(Math.floor(hpnow/hpmax*widb1)<=widb1)
							{
								h2.style.width = Math.floor(hpnow/hpmax*widb1)+'px';
							}
						}else{
							if(hpnow<0)
							{
								hpnow = 0;	
							}
							if(hpnow>hpmax)
							{
								hpnow = hpmax;	
							}
							h1.innerHTML = ' '+Math.floor(hpnow)+'/'+hpmax+'';
							if(Math.floor(hpnow/hpmax*widb1)<1)
							{
								h2.className = 'hp_none';	
							}
							if(Math.floor(hpnow/hpmax*widb1)>0)
							{
								h2.className = 'hp_1';
							}
							if(Math.floor(hpnow/hpmax*widb1)>32)
							{
								h2.className = 'hp_2';
							}
							if(Math.floor(hpnow/hpmax*widb1)>65)
							{
								h2.className = 'hp_3';
							}
							if(Math.floor(hpnow/hpmax*widb1)<=widb1)
							{
								h2.style.width = Math.floor(hpnow/hpmax*widb1)+'px';
							}
						}
				}
			}
			//мана
			var m1 = d.getElementById('vmp'+id);
			if(m1!=undefined)
			{
				var m2 = d.getElementById('lmp'+id);	
				if(m2!=undefined)
				{
					//Восстанавливаем MP
					var newMP = '';
						newMP = sec_m*speed_mp;
						if(newMP!=0)
						{
							mpnow += newMP;
							if(mpnow<0)
							{
								mpnow = 0;	
							}
							if(mpnow>mpmax)
							{
								mpnow = mpmax;	
							}
							m1.innerHTML = ' '+Math.floor(mpnow)+'/'+mpmax+'';
							if(Math.floor(mpnow/mpmax*widb1)<1)
							{
								m2.className = 'hp_none';	
							}else{
								m2.className = 'hp_mp';
							}
							if(Math.floor(mpnow/mpmax*widb1)<=widb1)
							{
								m2.style.width = Math.floor(mpnow/mpmax*widb1)+'px';
							}
						}else{
							if(mpnow<0)
							{
								mpnow = 0;	
							}
							if(mpnow>mpmax)
							{
								mpnow = mpmax;	
							}
							m1.innerHTML = ' '+Math.floor(mpnow)+'/'+mpmax+'';
							if(Math.floor(mpnow/mpmax*widb1)<1)
							{
								m2.className = 'hp_none';	
							}else{
								m2.className = 'hp_mp';
							}
							if(Math.floor(mpnow/mpmax*widb1)<=widb1)
							{
								m2.style.width = Math.floor(mpnow/mpmax*widb1)+'px';
							}
						}
				}
			}
			lafstReg[id] = 0+lafstReg[id]+1;
			var tt1 = setTimeout('top.startHpRegen("'+f+'",'+id+','+hpnow+','+hpmax+','+mpnow+','+mpmax+',1,1,'+speed_hp+','+speed_mp+',0,'+timerHP+');',1000);
		}
		if(lafstReg[id]==0 || lafstReg[id]==undefined)
		{
			lafstReg[id] = 0+lafstReg[id]+1;
			var tt1 = setTimeout('top.startHpRegen("'+f+'",'+id+','+hpnow+','+hpmax+','+mpnow+','+mpmax+',1,1,'+speed_hp+','+speed_mp+',0,'+timerHP+');',1000);
		}
	}
}