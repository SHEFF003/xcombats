<?
if(!defined('GAME'))
{
	die();
}

$stl = 15; //кол-во стилей клеток
?>
<table class="tblbr2" width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td width="300" valign="top">Главные настройки обьекта:</td>
    <td valign="top">Свойства обьекта:</td>
  </tr>
  <tr>
    <td valign="top"><p>Изображение:</p>
    <p align="center"><img src="" /></p></td>
    <td valign="top" class="tblbr"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="170"><div align="left">ID:</div></td>
        <td><div align="left">
          <input type="text" name="textfield" id="textfield" />
          <input type="submit" name="button" id="button" value="Загрузить шаблон" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Название:</div></td>
        <td><div align="left">
          <input type="text" name="textfield2" id="textfield2" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Изображение:</div></td>
        <td><div align="left">
          <input type="text" name="textfield3" id="textfield3" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Расположение:</div></td>
        <td><div align="left">
          <table border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox" name="checkbox" id="checkbox" /></td>
                <td>&nbsp;</td>
              </tr>
            <tr>
              <td><input type="checkbox" name="checkbox8" id="checkbox8" /></td>
                <td><input type="checkbox" name="checkbox5" id="checkbox5" /></td>
                <td><input type="checkbox" name="checkbox3" id="checkbox3" /></td>
              </tr>
            <tr>
              <td>&nbsp;</td>
                <td><input type="checkbox" name="checkbox6" id="checkbox6" /></td>
                <td>&nbsp;</td>
              </tr>
                  </table>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Стороны обзора:</div></td>
        <td><div align="left">
          <table border="0" cellspacing="5" cellpadding="0">
              <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox" name="checkbox2" id="checkbox2" /></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><input type="checkbox" name="checkbox2" id="checkbox4" /></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox7" /></td>
                <td><input type="checkbox" name="checkbox2" id="checkbox9" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="checkbox" name="checkbox2" id="checkbox10" /></td>
                <td>&nbsp;</td>
              </tr>
                  </table>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Тип:</div></td>
        <td><div align="left">
          <select name="select" id="select">
            <option value="0">пусто</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Высота изображения:</div></td>
        <td><div align="left">
          <input name="textfield4" type="text" id="textfield4" size="7" />
          px</div></td>
      </tr>
      <tr>
        <td><div align="left">Ширина изображения:</div></td>
        <td><div align="left">
          <input name="textfield5" type="text" id="textfield5" size="7" />
          px</div></td>
      </tr>
      <tr>
        <td><div align="left">Сдвиг вверх:</div></td>
        <td><div align="left">
              <input name="textfield6" type="text" id="textfield6" size="7" /> 
          %</div></td>
      </tr>
      <tr>
        <td><div align="left">Сдвиг влево:</div></td>
        <td><div align="left">
          <input name="textfield7" type="text" id="textfield7" size="7" />
          % </div></td>
      </tr>
      <tr>
        <td><div align="left"></div></td>
        <td><div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="left">Координаты X:</div></td>
        <td><div align="left">
          <input name="textfield8" type="text" id="textfield8" size="7" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Координаты Y:</div></td>
        <td><div align="left">
          <input name="textfield9" type="text" id="textfield9" size="7" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left"></div></td>
        <td><div align="left"></div></td>
      </tr>
      <tr>
        <td valign="top"><div align="left">Действия:</div></td>
        <td><p align="left">
          <textarea name="textarea" id="textarea" cols="45" rows="5"></textarea>
        </p>
          <p align="left"><a href="#">Инструкция к полю &quot;Действия&quot;</a></p></td>
      </tr>
    </table></td>
  </tr>
</table>
