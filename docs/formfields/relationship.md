# Relationship

This page describes the relationship formfield. For informations on how to use the relationship formfields visit [this page](../bread/relationships.md)!  

The relationship formfield is a powerful tool to display, search, select, add and even edit related items.  

It gets even more powerful when your related model contains a BREAD.  
This way you can select lists for browsing your relationship and views for adding more items directly on the same page.

![Relationship with a BREAD and a list to display items](../.gitbook/assets/bread/formfields/relationship/relationship.png) 

## Options

### Column

Select the relationship method as defined in your model.

### List for browsing

If the related model has a BREAD you can select a list which is used to browse related entries.

### View for adding

If the related model has a BREAD you can select a view to be used to add related items.  
Set to null to forbid adding related entries.

### Display column

The column that is displayed when adding/editing.

### Order column

The column that is used to order the related items.  
This is only shown and necessary when the related model does not have a BREAD.

### Show actions

Shows all action buttons (BREAD required)

### Display name

The name to be display in the table header.  
This field is translatable.