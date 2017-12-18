document.onreadystatechange=function() {
	
	h4=this.getElementsByTagName('h4');

	
	for (i=0;i<=h4.length; i++)  {
		
		h4[i].onclick=function () {
			h3=this.nextSibling.nextSibling;
			state=h3.style;
			
			if (state.display=='inline-block')  {
				
				state.display='none';
			}
			
			else { 
				state.display='inline-block'; 
			
			}

			
		}
	}
}